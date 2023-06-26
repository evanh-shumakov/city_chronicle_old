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
        $dateField = Field\DateTimeField::new('dateTime');
        $dateField->setFormTypeOption('attr', ['readonly' => 'readonly']);

        if ($isPageNew) {
            $dateField->setFormTypeOption('data', new \DateTime());
        }

        yield Field\TextField::new('name');
        yield $dateField;
        yield Field\TextareaField::new('description');
    }
}
