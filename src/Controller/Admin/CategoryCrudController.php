<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }


    public function configureFields(string $pageName): iterable
    {
        //configuration des champs de l'entité
        return [
            //on cache le champs id et les dates
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'nom'),
            BooleanField::new('active'),
            DateTimeField::new('updatedAt')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }

    public function persistEntity( EntityManagerInterface $entityManager, $entityInstance) : void
    {
        if(!$entityInstance instanceof Category) {
            return;
        }

        $entityInstance->setCreatedAt(new DateTimeImmutable());

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function deleteEntity(EntityManagerInterface $em, $entityInstance) : void
    {
        if(!$entityInstance instanceof Category){
            return;
        }

        //pour chaque produits liés à une catégorie supprimé on supprime les produits
        foreach ($entityInstance->getProducts() as $product){
            $em->remove($product);
        }

        parent::deleteEntity($em, $entityInstance);
    }
}
