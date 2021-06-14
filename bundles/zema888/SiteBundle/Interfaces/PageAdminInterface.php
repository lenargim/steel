<?php
namespace SiteBundle\Interfaces;


interface PageAdminInterface
{
    /**
     * Название Админского класса
     * @return string
     */
    public function getAdminClass(): string;
}