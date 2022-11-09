<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email')->setLabel("Электронная почта"),
            ArrayField::new('roles')->setLabel("Роли"),
            DateField::new('date')->setLabel("Дата регистрации"),
            TextField::new('username')->setLabel("Имя пользователя"),
            TextField::new('firstname')->setLabel("Имя"),
            TextField::new('lastname')->setLabel("Фамилия"),
            AssociationField::new('subscriber')->setLabel("Подписки")
        ];
    }


}
