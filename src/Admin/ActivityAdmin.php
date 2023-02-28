<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class ActivityAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('label')
            ->add('heartRate')
            ->add('isDistance')
            ->add('client')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('label')
            ->add('heartRate')
            ->add('isDistance')
            ->add('client')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Général', ['class' => 'col-md-6'])
            ->add('label')
            ->add('client', null, ['placeholder' => 'Choisir un client', 'help' => 'Laisser vide si disponible pour tous les clients'])
            ->end()
            ->with('Configuration', ['class' => 'col-md-6'])
            ->add('heartRate', null, ['help' => 'Par minute'])
            ->add('isDistance')
        ;
    }
}
