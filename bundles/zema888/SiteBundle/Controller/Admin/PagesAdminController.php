<?php
namespace SiteBundle\Controller\Admin;



use ZemaTreeBundle\Controller\TreeAdminController;

//use RedCode\TreeBundle\Controller\TreeAdminController;

class PagesAdminController extends TreeAdminController
{
    public function listAction() {
        $this->admin->setListModes([
                                       'tree' => [
                                           'class' => 'fa fa-tree fa-fw',
                                       ],
                                       'list' => [
                                           'class' => 'fa fa-list fa-fw',
                                       ],
                                   ]);
        return parent::listAction();
    }
}
