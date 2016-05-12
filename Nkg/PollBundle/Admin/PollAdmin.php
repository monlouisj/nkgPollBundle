<?php
namespace Nkg\PollBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Nkg\PollBundle\Entity\Poll;

class PollAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Le sondage', array('class' => 'col-md-6'))
              ->add('libelle', 'text', array('label' => 'Nom du sondage'))
              ->add('description', 'text', array('label' => 'Description du sondage', 'required'=>false))
              ->add('startdate', 'datetime', array('label' => 'Date de début'))
              ->add('enddate', 'datetime', array('label' => 'Date de fin'))
              ->add('active', 'checkbox', array('label' => 'Actif?','required'=>false))
            ->end()
            ->with('Les reponses', array('class' => 'col-md-6'))
              ->add('opinions', 'sonata_type_collection', array(
                    'label'       => "Veuillez saisir les réponses proposées: ",
                    'by_reference'       => false,
                    'cascade_validation' => true,
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table'
                ))
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('libelle')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('libelle', null, array("label"=>"Titre"))
            ->add('startdate', null, array("label"=>"Date de début"))
            ->add('enddate', null, array("label"=>"Date de fin"))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $poll = parent::getNewInstance();
        $debut = $poll->getStartdate();

        if($debut === null){
          $debut = new \Datetime("now");
          $poll->setStartdate($debut);
        }


        if($poll->getEnddate() === null){
          $fin = clone $debut;
          $fin = $fin->add( new \DateInterval("P10D"));
          $poll->setEnddate($fin);
        }

        return $poll;
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        //set poll for opinions
        foreach($object->getOpinions() as $opinion) {
            $opinion->setPoll($object);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        //set poll for opinions
        foreach($object->getOpinions() as $opinion) {
            $opinion->setPoll($object);
        }
    }
}
