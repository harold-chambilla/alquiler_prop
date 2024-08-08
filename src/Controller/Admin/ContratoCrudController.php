<?php

namespace App\Controller\Admin;

use App\Entity\Arrendatario;
use App\Entity\Contrato;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContratoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contrato::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Contrato')
            ->setEntityLabelInPlural('Contratos')
            ->setPageTitle(Crud::PAGE_INDEX, 'Gestión de Contratos')
            ->setDefaultSort(['co_fecha_ingreso' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Campos del contrato
            DateField::new('co_fecha_ingreso', 'Fecha de Ingreso')
                ->setRequired(true),
            // DateField::new('co_fecha_nacimiento', 'Fecha de Nacimiento')
            //     ->setRequired(true),    
    
            // FormField::addPanel('Cuarto de Arrendatario'),
            
            AssociationField::new('piso_id', 'Piso')
            ->setRequired(true)
            ->setCrudController(PisoCrudController::class),
            
            // FormField::addPanel('Información del Arrendatario'),
            TextField::new('arrendatario_id.ao_nombres', 'Nombres del Arrendatario')
                ->setFormTypeOption('required', true),
            TextField::new('arrendatario_id.ao_apellidos', 'Apellidos del Arrendatario')
                ->setFormTypeOption('required', true),
            TextField::new('arrendatario_id.ao_telefono', 'Teléfono del Arrendatario'),
            TextField::new('arrendatario_id.ao_tipo', 'Tipo de Arrendatario'),
            TextField::new('arrendatario_id.ao_cedula_identidad', 'Cédula de Identidad'),
            DateField::new('arrendatario_id.ao_fecha_nacimiento', 'Fecha de Nacimiento del Arrendatario'),

            MoneyField::new('co_alquiler_mensual', 'Alquiler Mensual')
                ->setCurrency('PEN')
                ->setStoredAsCents(false)
                ->setNumDecimals(2),
            MoneyField::new('co_agua', 'Agua')
                ->setCurrency('PEN')
                ->setStoredAsCents(false)
                ->setNumDecimals(2),
            // NumberField::new('co_alquiler_mensual', 'Alquiler Mensual')
            //     ->setStoredAsString('number')
            //     ->setNumDecimals(2)
            //     ->setHelp('Solo se permiten números'),
            // FormField::addPanel('')
    
                // FormField::addPanel('Información Adicional'),
                // MoneyField::new('renta_mensual', 'Renta Mensual')->setCurrency('USD'),
                // MoneyField::new('costo_agua', 'Costo del Agua')->setCurrency('USD'),
                // NumberField::new('otros_gastos', 'Otros Gastos'),

            // Nuevos campos
            // NumberField::new('co_alquiler_mensual', 'Alquiler Mensual')
            //     ->setRequired(true),
            // NumberField::new('co_costo_agua', 'Costo del Agua')
            //     ->setRequired(true),
            // TextField::new('co_otro_dato', 'Otro Dato'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $downloadPdf = Action::new('downloadPdf', 'Descargar PDF')
            ->linkToRoute('contrato_pdf', function (Contrato $contrato) {
                return [
                    'id' => $contrato->getId(),
                ];
            })
            ->setHtmlAttributes(['target' => '_blank']);

        return $actions
            ->add(Crud::PAGE_INDEX, $downloadPdf)
            ->add(Crud::PAGE_DETAIL, $downloadPdf);
    }

    public function createEntity(string $entityFqcn)
    {
        $contrato = new Contrato();
        
        // Inicializamos un nuevo arrendatario
        $arrendatario = new Arrendatario();
        $contrato->setArrendatarioId($arrendatario);

        return $contrato;
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
