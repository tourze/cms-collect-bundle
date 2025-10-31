<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Tourze\CmsCollectBundle\Entity\CollectLog;

#[AdminDashboard(routePath: '/cms-collect-test-admin', routeName: 'cms_collect_test_admin')]
class TestDashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return new Response('Test Dashboard');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Test Dashboard')
        ;
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('YYYY-MM-dd')
            ->setDateTimeFormat('YYYY-MM-dd HH:mm:ss')
            ->showEntityActionsInlined()
            ->renderContentMaximized()
        ;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addAssetMapperEntry('admin')
        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('首页', 'fa fa-home');
        yield MenuItem::linkToCrud('收藏记录', 'fas fa-star', CollectLog::class);
    }
}
