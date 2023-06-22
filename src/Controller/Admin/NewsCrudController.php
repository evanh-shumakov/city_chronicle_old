<?php

namespace App\Controller\Admin;

use App\Entity\News;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field;

class NewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return News::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $isPageEdit = $pageName === Crud::PAGE_EDIT;
        $isPageNew = $pageName === Crud::PAGE_NEW;

        yield Field\TextField::new('name');
        yield Field\DateTimeField::new('dateTime')
            ->setFormTypeOption('disabled', $isPageEdit || $isPageNew);
        yield Field\TextareaField::new('description');
    }
}
