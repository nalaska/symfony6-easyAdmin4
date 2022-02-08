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
        $url = $this->adminUrlGenerator
            ->setController(ProductCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Website');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('E-commerce');
        yield MenuItem::section('Product');

        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add product', 'fas fa-plus', Product::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show product', 'fas fa-eye', Product::class)
        ]);

        yield MenuItem::section('Categories');
        yield MenuItem::subMenu('Action', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add category', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show category', 'fas fa-eye', Category::class)
        ]);
    }
}
