<?php
namespace Nkg\PollBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints as Assert;

class OpinionAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('libelle', 'text', array('label' => 'Libelle reponse'))
            ->add('description', 'text', array('label' => 'Description reponse'))
            ->add('poll')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('libelle')
            ->add('votes')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('libelle', null, array("label"=>"Libellé"))
            ->add('description', null, array("label"=>"Description"))
            ->add('votes', null, array("label"=>"Nombre de votes"))
        ;
    }
}
