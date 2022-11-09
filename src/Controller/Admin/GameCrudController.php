<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('studio')->setLabel("Студия"),
            AssociationField::new('publisher')->setLabel("Издательство"),
            TextField::new('name')->setLabel("Название"),
            TextField::new('genre')->setLabel("Жанр"),
            DateField::new('announceDate')->setLabel("Дата анонса"),
            DateField::new('releaseDate')->setLabel("Дата выхода"),
            ImageField::new('photo')->setUploadDir("public/images/")->setLabel("Обложка"),
            TextareaField::new('description')->setLabel("Описание"),
        ];
    }

}
