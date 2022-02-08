<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

class ProductCrudController extends AbstractCrudController
{
    public const PRODUCTS_BASE_PATH = 'upload/images/products';
    public const PRODUCTS_UPLOAD_DIR = 'public/upload/images/products';
    public const ACTION_DUPLICATE = 'DUPLICATE';

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions) : Actions
    {
        $duplicate = Action::new(self::ACTION_DUPLICATE)
            ->linkToCrudAction('duplicateProduct')
            ->setCssClass('btn btn-info')
        ;
        return $actions
            ->add(Crud::PAGE_EDIT, $duplicate);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextEditorField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
            BooleanField::new('active'),
            ImageField::new('image')
                ->setBasePath(self::PRODUCTS_BASE_PATH)
                ->setUploadDir(self::PRODUCTS_UPLOAD_DIR)
                ->setSortable(false),
            AssociationField::new('category')->setQueryBuilder(static function (QueryBuilder $builder) {
                $builder->where('entity.active = true');
            }),
            DateTimeField::new('updatedAt')->hideOnForm(),
            DateTimeField::new('createdAt')->hideOnForm(),
        ];
    }

   /* public function persistEntity( EntityManagerInterface $em, $entityInstance) : void
    {
        if(!$entityInstance instanceof Product) {
            return;
        }

        $entityInstance->setCreatedAt(new DateTimeImmutable());

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance) : void
    {
        if(!$entityInstance instanceof Product) {
            return;
        }

        $entityInstance->setUpdatedAt(new DateTimeImmutable());

        parent::persistEntity($em, $entityInstance);
    }*/

    public function duplicateProduct(
        AdminContext $adminContext,
        EntityManagerInterface $em,
        AdminUrlGenerator $urlGenerator
    ) : Response
    {
        /** @var Product $product */
        $product = $adminContext->getEntity()->getInstance();

        $duplicate = clone $product;
        parent::persistEntity($em, $duplicate);

        $url = $urlGenerator->setController(self::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($duplicate->getId())
            ->generateUrl();

        return $this->redirect($url);
    }
}
