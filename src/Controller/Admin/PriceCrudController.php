<?php

namespace App\Controller\Admin;

use App\Entity\Price;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PriceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Price::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('platform')->setLabel("Платформа"),
            AssociationField::new('game')->setLabel("Игра"),
            NumberField::new('cost')->setLabel("Цена"),
            NumberField::new('discount')->setLabel("Скидка"),
        ];
    }

}
