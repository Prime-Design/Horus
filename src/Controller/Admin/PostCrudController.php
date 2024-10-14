<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->onlyOnIndex(),
            TextField::new('artist', 'Artiste')->setColumns('col-md-6'),
            TextField::new('city', 'Ville')->setColumns('col-md-6'),
            TextField::new('Lieux')->setColumns('col-md-6'),
            TextField::new('Date')->setColumns('col-md-6'),
            TextField::new('price', 'Prix')->setColumns('col-md-6'),
            TextField::new('content', 'Texte')->setColumns('col-md-6'),
            TextField::new('link')->setColumns('col-md-6'),
            $image = ImageField::new('image')
                ->setUploadDir('public/divers/images')
                ->setBasePath('divers/images')
                ->setSortable(false)
                ->setFormTypeOption('required', false)
                ->setColumns('col-md-2'),
            AssociationField::new('rubrik','Rubrique')->setColumns('col-md-4'),
            AssociationField::new('user')->setColumns('col-md-6'),
            DateField::new('createdAt', 'Date de création')->onlyOnIndex(),
            BooleanField::new('isPublished')
                ->setColumns('col-md-1')
                ->setLabel('Publié'),
        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Un article')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(5);
    }
    // Configurer les filtres 
    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('user')
            ->add('artist')
            ->add('rubrik')
            ->add('createdAt');
    }
    // Mise en place des actions possibles selon le rôle
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }
}
