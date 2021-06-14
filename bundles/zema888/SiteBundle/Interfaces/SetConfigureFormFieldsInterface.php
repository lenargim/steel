<?php
namespace SiteBundle\Interfaces;

use Sonata\AdminBundle\Form\FormMapper;


interface SetConfigureFormFieldsInterface
{
    public static function setConfigureFormFields(FormMapper $formMapper, $subject);
}