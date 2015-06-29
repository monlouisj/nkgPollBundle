<?php

namespace nkgPollBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle', 'text');
        $builder->add('description', 'textarea');
        $builder->add('startdate', 'datetime');
        $builder->add('enddate', 'datetime');
        $builder->add('active', 'checkbox');
        $builder->add('VALIDER', 'submit');
    }

    public function getName()
    {
        return 'poll';
    }
}
