<?php

namespace App\Controller\CRM;

use App\Entity\Contrato;
use App\Entity\Arrendatario;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
        
        $fields = [];
        $fields[] = DateField::new('co_fecha_ingreso', 'Fecha de Ingreso')
            ->setRequired(true);
        $fields[] = AssociationField::new('piso_id', 'Piso')
            ->setRequired(true)
            ->setCrudController(PisoCrudController::class);

        if ($pageName === Crud::PAGE_INDEX) {
            // Mostrar el campo combinado en la vista de índice
            $fields[] = AssociationField::new('arrendatario_id', 'Arrendatario')
                ->formatValue(function ($value, $entity) {
                    if ($entity && $entity->getArrendatarioId()) {
                        return sprintf(
                            '%s %s (DNI: %s)',
                            $entity->getArrendatarioId()->getAoNombres(),
                            $entity->getArrendatarioId()->getAoApellidos(),
                            $entity->getArrendatarioId()->getAoCedulaIdentidad()
                        );
                    }
                    return '';
                });
        } else {
            // Mostrar todos los campos en la vista de edición/agregado
            $fields[] = FormField::addPanel('Información del Arrendatario');
            $fields[] = TextField::new('arrendatario_id.ao_nombres', 'Nombres del Arrendatario')
                ->setFormTypeOption('required', true);
            $fields[] = TextField::new('arrendatario_id.ao_apellidos', 'Apellidos del Arrendatario')
                ->setFormTypeOption('required', true);
            $fields[] = TextField::new('arrendatario_id.ao_telefono', 'Teléfono del Arrendatario')
                ->setRequired(true);
            $fields[] = TextField::new('arrendatario_id.ao_tipo', 'Tipo de Arrendatario')
                ->setRequired(true);
            $fields[] = TextField::new('arrendatario_id.ao_cedula_identidad', 'Cédula de Identidad')
                ->setRequired(true);
            $fields[] = DateField::new('arrendatario_id.ao_fecha_nacimiento', 'Fecha de Nacimiento del Arrendatario')
                ->setRequired(true);
        }
    
        // Otros campos del contrato (comunes para todas las páginas)
        $fields[] = MoneyField::new('co_alquiler_mensual', 'Alquiler Mensual')
            ->setCurrency('PEN')
            ->setStoredAsCents(false)
            ->setNumDecimals(2)->setRequired(true);
        $fields[] = MoneyField::new('co_agua', 'Agua')
            ->setCurrency('PEN')
            ->setStoredAsCents(false)
            ->setNumDecimals(2)->setRequired(true);
            if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_EDIT) {
                $fields[] = ChoiceField::new('co_estado', 'Estado contrato')
                    ->setChoices([
                        'Activo' => 1,
                        'Terminado' => 0,
                    ])
                    ->renderAsBadges([
                        1 => 'success',   // verde para "Activo"
                        0 => 'danger',    // rojo para "Terminado"
                    ])
                    ->formatValue(function ($value, $entity) {
                        return $value == 1 ? 'Activo' : 'Terminado';
                    });
            }
            
    
        return $fields;
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
            ->add(Crud::PAGE_DETAIL, $downloadPdf)
            ->disable(Action::DELETE);
    }

    public function createEntity(string $entityFqcn)
    {
        $contrato = new Contrato();
        
        // Inicializamos un nuevo arrendatario
        $arrendatario = new Arrendatario();
        $contrato->setArrendatarioId($arrendatario);
        $contrato->setCoEstado(1);
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
