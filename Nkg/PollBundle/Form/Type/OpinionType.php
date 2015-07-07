<?php

namespace Nkg\PollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle', 'text');
        $builder->add('description', 'textarea');
        $builder->add('VALIDER', 'submit');
    }

    public function getName()
    {
        return 'opinion';
    }
}
