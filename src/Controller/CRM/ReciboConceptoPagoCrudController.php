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
            DateTimeField::new('rcp_fecha_digitacion', 'Fecha Digitación')
                ->setRequired(true),

            // AssociationField::new('recibo_id', 'Recibo')
            //     ->hideOnForm(),

            // CollectionField::new('concepto_pago_id', 'Conceptos de Pago')
            //     ->setEntryType(ConceptoPagoCrudController::class)
            //     ->setRequired(true),
        ];
    }
}
