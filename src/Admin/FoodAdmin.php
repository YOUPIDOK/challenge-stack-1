<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class FoodAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('label')
            ->add('calories')
            ->add('carbohydrates')
            ->add('lipids')
            ->add('proteins')
            ->add('client')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('label')
            ->add('calories')
            ->add('carbohydrates')
            ->add('lipids')
            ->add('proteins')
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
            ->with('Valeurs nutritionelles', ['class' => 'col-md-6'])
            ->add('calories', null, ['help' => 'Kcal pour 100g'])
            ->add('carbohydrates', null, ['help' => 'Pour 100g'])
            ->add('lipids', null, ['help' => 'Pour 100g'])
            ->add('proteins', null, ['help' => 'Pour 100g'])
            ;
    }
}
