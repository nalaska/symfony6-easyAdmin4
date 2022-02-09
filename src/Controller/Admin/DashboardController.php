<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //générateur d'url pour le composant easyAdmin qui permet de rediriger vers le controller crud fait avec la commande symfony console make:admin:crud
        $url = $this->adminUrlGenerator
            ->setController(ProductCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Produit');

        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            //on renvoie vers la page d'ajout du crud qui est en rapport avec l'entité Product
            MenuItem::linkToCrud('Ajouter un produit', 'fas fa-plus', Product::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les produits', 'fas fa-eye', Product::class)
        ]);

        yield MenuItem::section('Catégorie');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            //on renvoie vers la page d'ajout du crud qui est en rapport avec l'entité Category
            MenuItem::linkToCrud('Ajouter une catégorie', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les catégories', 'fas fa-eye', Category::class)
        ]);
    }
}
