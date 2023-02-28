<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Repository\User\UserRepository;
use DateTime;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

final class ClientAdmin extends AbstractAdmin
{

    public function __construct(?string $code = null, ?string $class = null, ?string $baseControllerName = null)
    {
        parent::__construct($code, $class, $baseControllerName);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('birthdate')
            ->add('height');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('birthdate', null, ['format' => 'd/m/Y'])
            ->add('height')
            ->add('registeredAt', null, ['format' => 'd/m/Y H:i'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $user = $this->getSubject()?->getUser();

        $form
            ->with('Client', ['class' => 'col-md-6'])
            ->add('user', null, [
                'required' => true,
                'query_builder' => function(UserRepository $userRepo) {
                    return $userRepo->userWithNoClientQb($this->getSubject()?->getUser());
                }
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new LessThanOrEqual(['value' => new DateTime('-15years'), 'message' => 'Minimum 15 ans'])
                ]
            ])
            ->add('height', null, ['help' => 'En cm'])
            ;
    }
}
