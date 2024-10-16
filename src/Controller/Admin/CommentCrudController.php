<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('content', 'Texte')->setColumns('col-md-6'),
            DateField::new('createdAt', 'Date de création')->onlyOnIndex(),
            TextField::new('post.artist', "Nom de l'artiste")->onlyOnIndex(),
            TextField::new('post.rubrik.name', 'Nom de la rubrique')->onlyOnIndex(),
            AssociationField::new('user', 'Pseudo')->setColumns('col-md-4'),

        ];
    }
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('createdAt');
    }
 

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW) // Désactiver la création
            ->disable(Action::EDIT)
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_MODO');


    }
}
