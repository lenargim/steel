<?php
namespace SiteBundle\SonataBlock;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonsBlock extends AbstractBlockService
{
    public function getName()
    {
        return 'My Newsletter';
    }
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'buttons' => [
                [
                    'url' => '#',
                    'title' => 'Insert the title',
                    'icon' => 'fa-font-awesome',
                    'info' => '',
                ]
            ],
            'template' => 'SiteBundle:sonata-block:buttons.html.twig',
        ]);
    }


    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = $blockContext->getSettings();

        return $this->renderResponse($blockContext->getTemplate(), [
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ], $response);
    }
}