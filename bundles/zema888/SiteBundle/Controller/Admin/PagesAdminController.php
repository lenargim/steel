<?php
namespace SiteBundle\Controller\Admin;



use App\Import\Application\Services\ParseService;
use App\Import\Domain\Entity\Item;
use Doctrine\Migrations\Generator\Exception\InvalidTemplateSpecified;
use SiteBundle\Entity\Pages\CatalogArticle;
use SiteBundle\Entity\Pages\CatalogItem;
use Sonata\AdminBundle\Exception\LockException;
use Sonata\AdminBundle\Exception\ModelManagerException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use ZemaTreeBundle\Controller\TreeAdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class PagesAdminController extends TreeAdminController
{
    /**
     * Edit action.
     *
     * @param int|string|null $deprecatedId
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     *
     * @return Response|RedirectResponse
     */
    public function editAction($deprecatedId = null) // NEXT_MAJOR: Remove the unused $id parameter
    {
        if (isset(\func_get_args()[0])) {
            @trigger_error(sprintf(
                'Support for the "id" route param as argument 1 at `%s()` is deprecated since'
                .' sonata-project/admin-bundle 3.62 and will be removed in 4.0,'
                .' use `AdminInterface::getIdParameter()` instead.',
                __METHOD__
            ), E_USER_DEPRECATED);
        }

        // the key used to lookup the template
        $templateKey = 'edit';

        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        $existingObject = $this->admin->getObject($id);

        if (!$existingObject) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $this->checkParentChildAssociation($request, $existingObject);

        $this->admin->checkAccess('edit', $existingObject);

        $preResponse = $this->preEdit($request, $existingObject);
        if (null !== $preResponse) {
            return $preResponse;
        }

        $this->admin->setSubject($existingObject);
        $objectId = $this->admin->getNormalizedIdentifier($existingObject);

        $form = $this->admin->getForm();

        $form->setData($existingObject);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                /** @phpstan-var T $submittedObject */
                $submittedObject = $form->getData();
                $this->admin->setSubject($submittedObject);

                try {
                    $existingObject = $this->admin->update($submittedObject);

                    if ($this->isXmlHttpRequest()) {
                        return $this->handleXmlHttpRequestSuccessResponse($request, $existingObject);
                    }

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->trans(
                            'flash_edit_success',
                            ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))],
                            'SonataAdminBundle'
                        )
                    );

                    // redirect to edit mode
                    return $this->redirectTo($existingObject);
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);

                    $isFormValid = false;
                } catch (LockException $e) {
                    $this->addFlash('sonata_flash_error', $this->trans('flash_lock_error', [
                        '%name%' => $this->escapeHtml($this->admin->toString($existingObject)),
                        '%link_start%' => sprintf('<a href="%s">', $this->admin->generateObjectUrl('edit', $existingObject)),
                        '%link_end%' => '</a>',
                    ], 'SonataAdminBundle'));
                }
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                if ($this->isXmlHttpRequest() && null !== ($response = $this->handleXmlHttpRequestErrorResponse($request, $form))) {
                    return $response;
                }

                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans(
                        'flash_edit_error',
                        ['%name%' => $this->escapeHtml($this->admin->toString($existingObject))],
                        'SonataAdminBundle'
                    )
                );
            } elseif ($this->isPreviewRequested()) {
                // enable the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        // NEXT_MAJOR: Remove this line and use commented line below it instead
        $template = $this->admin->getTemplate($templateKey);
        // $template = $this->templateRegistry->getTemplate($templateKey);

        $importform = $this->createImportForm();

        return $this->renderWithExtraParams($template, [
            'action' => 'edit',
            'form' => $formView,
            'object' => $existingObject,
            'objectId' => $objectId,
            'importform' => $importform->createView(),
        ], null);
    }

    /**
     * @return FormInterface
     */
    private function createImportForm(): FormInterface
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var CatalogArticle $category */
        $category = $this->admin->getObject($id);
        return $this->createFormBuilder()
            ->setAction($this->admin->generateObjectUrl('importArticle', $category))
            ->add('file', FileType::class, [
                'label' => 'Файл импорта',
                'required' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Начать импорт'])
            ->getForm();
    }
    /**
     * @return Response
     */
    public function exportArticleAction()
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var CatalogArticle $category */
        $category = $this->admin->getObject($id);
        $items = $category->getChildren([CatalogItem::class]);
        $result = $this->admin->parseService->serialize($items);
        $response = new Response($result);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            sprintf('attachment; filename="export-article-%d.csv"', $category->getId())
        );
        return $response;
    }

    /**
     * @return Response
     */
    public function importArticleAction()
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var CatalogArticle $category */
        $category = $this->admin->getObject($id);
        $form = $this->createImportForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $importData = $form->getData();
            $importFilePath = $importData['file']->getPathName();
            if ($importFilePath) {
                $importItems = $this->admin->parseService->deserialize($importFilePath);
                $updated = $this->admin->updateService->execute(
                    $category,
                    $importItems
                );

                return new Response(
                    sprintf('успешно обновлены %d товаров. <a href="%s">Перейти к списку</a>', $updated, $this->admin->generateUrl('list'))
                );
            }
        }

        return $this->redirectTo($category);
    }

    /**
     * @phpstan-param T $object
     */
    private function checkParentChildAssociation(Request $request, object $object): void
    {
        if (!$this->admin->isChild()) {
            return;
        }

        // NEXT_MAJOR: remove this check
        if (!$this->admin->getParentAssociationMapping()) {
            return;
        }

        $parentAdmin = $this->admin->getParent();
        $parentId = $request->get($parentAdmin->getIdParameter());

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $propertyPath = new PropertyPath($this->admin->getParentAssociationMapping());

        if ($parentAdmin->getObject($parentId) !== $propertyAccessor->getValue($object, $propertyPath)) {
            // NEXT_MAJOR: make this exception
            @trigger_error(
                'Accessing a child that isn\'t connected to a given parent is deprecated since sonata-project/admin-bundle 3.34 and won\'t be allowed in 4.0.',
                E_USER_DEPRECATED
            );
        }
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView, ?array $theme = null): void
    {
        $twig = $this->get('twig');

        $twig->getRuntime(FormRenderer::class)->setTheme($formView, $theme);
    }
}
