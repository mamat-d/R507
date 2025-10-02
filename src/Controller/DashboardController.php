<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(
    routePath: '/admin',
    routeName: 'admin',
    routeOptions: [
        "requirements" => ['domain' => '%app_base_domain%'],
        "defaults" => ['domain' => '%app_base_domain%'],
        "host" => '{domain}',
    ],
)]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_contact_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('R507');
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setTimezone('Europe/Paris')
            ->showEntityActionsInlined()
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityPermission('ROLE_ADMIN');
    }
}
