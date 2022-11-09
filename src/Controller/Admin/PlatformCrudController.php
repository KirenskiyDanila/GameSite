<?php

namespace App\Controller\Admin;

use App\Entity\Platform;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlatformCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Platform::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel("Название"),
            DateField::new('date')->setLabel("Дата выхода"),
            TextareaField::new('description')->setLabel("Описание"),
        ];
    }

}
