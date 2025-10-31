<?php

declare(strict_types=1);

namespace Tourze\CmsCollectBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use Tourze\CmsCollectBundle\Entity\CollectLog;

#[AdminCrud(
    routePath: '/cms-collect/collect-log',
    routeName: 'cms_collect_collect_log'
)]
final class CollectLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CollectLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('收藏记录')
            ->setEntityLabelInPlural('收藏记录管理')
            ->setPageTitle(Crud::PAGE_INDEX, '收藏记录列表')
            ->setPageTitle(Crud::PAGE_NEW, '新建收藏记录')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑收藏记录')
            ->setPageTitle(Crud::PAGE_DETAIL, '收藏记录详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['entity.title', 'user.username'])
            ->showEntityActionsInlined()
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield AssociationField::new('entity', '实体内容')
            ->setColumns('col-md-6')
            ->setRequired(true)
        ;

        yield AssociationField::new('user', '用户')
            ->setColumns('col-md-6')
            ->setRequired(false)
        ;

        yield BooleanField::new('valid', '有效状态')
            ->renderAsSwitch(false)
        ;

        yield TextField::new('createdFromIp', '创建IP')
            ->onlyOnDetail()
        ;

        yield TextField::new('updatedFromIp', '更新IP')
            ->onlyOnDetail()
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->onlyOnDetail()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;

        yield AssociationField::new('createdBy', '创建者')
            ->onlyOnDetail()
        ;

        yield AssociationField::new('updatedBy', '更新者')
            ->onlyOnDetail()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('valid', '有效状态'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }
}
