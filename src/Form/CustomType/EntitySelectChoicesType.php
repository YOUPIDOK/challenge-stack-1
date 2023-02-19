<?php

namespace App\Form\CustomType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntitySelectChoicesType extends AbstractSelectChoicesType
{
    public function getParent()
    {
        return EntityType::class;
    }
}