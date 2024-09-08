<?php

namespace App\Controller\CRM;

use App\Entity\Piso;
use App\Controller\CRM\ResidenciaCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PisoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Piso::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('pi_posicion', 'Posición'),
            TextField::new('pi_cuarto', 'Cuarto'),
            TextField::new('pi_zona', 'Zona'),
            AssociationField::new('residencia_id', 'Residencia')
                ->setRequired(true)
                ->setCrudController(ResidenciaCrudController::class),
        ];
    
        // Condición para mostrar el campo de "Estado piso" solo en las páginas de índice y edición
        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_EDIT) {
            $fields[] = ChoiceField::new('pi_estado', 'Estado piso')
                ->setChoices([
                    'Disponible' => 1,
                    'No disponible' => 0,
                ])
                ->renderAsBadges([
                    1 => 'success',   // Verde para "Disponible"
                    0 => 'danger',    // Rojo para "No disponible"
                ])
                ->formatValue(function ($value) {
                    return $value == 1 ? 'Disponible' : 'No disponible';
                });
        }
    
        return $fields;
    }

    public function createEntity(string $entityFqcn)
    {
        $piso = new Piso();
        $piso->setPiEstado(1);
        return $piso;
    }
}
