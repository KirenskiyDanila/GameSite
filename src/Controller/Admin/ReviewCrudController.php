<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use phpDocumentor\Reflection\Types\Integer;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('game')->setLabel("Игра"),
            AssociationField::new('author')->setLabel("Автор"),
            AssociationField::new('admin')->setLabel("Администратор, проверивший обзор"),
            IntegerField::new('grade')->setLabel("Оценка"),
            DateField::new('date')->setLabel("Дата публикации"),
            TextareaField::new('text')->setLabel("Текст обзора"),
            BooleanField::new('approved')->setLabel("Одобрено?")
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('approved')
            ->add('admin')
            ->add('game')
            ->add('date')
            ;
    }

}
