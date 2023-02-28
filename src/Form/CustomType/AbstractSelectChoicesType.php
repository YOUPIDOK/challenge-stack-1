<?php

namespace App\Form\CustomType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSelectChoicesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label_attr' => [
                'class' => 'choices__label'
            ]
        ]);

        parent::configureOptions($resolver);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $defaultAttributs = [
            'data-controller' => 'form--choices',
            'data-form--choices-target' => 'select',
        ];

        $view->vars['attr'] = array_merge($view->vars['attr'], $defaultAttributs);
    }
}