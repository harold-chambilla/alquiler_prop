<?php

namespace App\Controller\CRM;

use App\Entity\ReciboConceptoPago;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ReciboConceptoPagoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReciboConceptoPago::class;
    }

    // public function createEntity(string $entityFqcn)
    // {
    //     $reciboConceptoPago = new ReciboConceptoPago();

    //     /** @var Recibo $recibo */
    //     $recibo = $this->getContext()->getEntity()->getInstance();
    //     // $reciboConceptoPago->setReciboId($recibo);
    //     $reciboConceptoPago->setRcpFechaDigitacion(new \DateTime());

    //     return $reciboConceptoPago;
    // }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Panel para la información de fecha de digitación
            FormField::addPanel('Datos de Digitación')
                ->setIcon('fa fa-calendar-check')
                ->setHelp('Por favor, selecciona la fecha y hora de digitación.')
                ->addCssClass('bg-light p-3 rounded'),

            DateTimeField::new('rcp_fecha_digitacion', 'Fecha de Digitación')
                ->setColumns('col-md-6 col-lg-4')
                ->setFormTypeOption('widget', 'single_text')
                ->setFormTypeOption('attr', [
                    'class' => 'form-control',
                    'placeholder' => 'Selecciona la fecha de digitación',
                    'style' => 'border-radius: 10px; box-shadow: 0px 4px 8px rgba(0,0,0,0.1);'
                ])
                ->setHelp('Formato: YYYY-MM-DD HH:MM'),

            // Panel para la información del recibo
            FormField::addPanel('Información del Recibo')
                ->setIcon('fa fa-receipt')
                ->addCssClass('bg-light p-3 rounded'),

            AssociationField::new('recibo_id', 'Recibo')
                ->hideOnForm()
                ->setColumns('col-md-6 col-lg-4')
                ->setFormTypeOption('attr', [
                    'class' => 'form-control',
                    'style' => 'border-radius: 10px;'
                ]),

            // Si decides habilitar el campo de Conceptos de Pago, aquí tienes un ejemplo:
            /*
            FormField::addPanel('Conceptos de Pago')
                ->setIcon('fa fa-coins')
                ->setHelp('Agregar o editar los conceptos de pago del recibo')
                ->addCssClass('bg-light p-3 rounded'),

            CollectionField::new('concepto_pago_id', 'Conceptos de Pago')
                ->setEntryType(ConceptoPagoCrudController::class)
                ->allowAdd()
                ->allowDelete()
                ->setColumns('col-md-12')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'label' => false,
                ]),
            */
        ];
    }


}
