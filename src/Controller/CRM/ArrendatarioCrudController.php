<?php

namespace App\Controller\CRM;

use App\Entity\Arrendatario;
use App\Repository\ArrendatarioRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArrendatarioCrudController extends AbstractCrudController
{
    private $arrendatarioRepository;

    public function __construct(ArrendatarioRepository $arrendatarioRepository)
    {
        $this->arrendatarioRepository = $arrendatarioRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Arrendatario::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        // Agrupamos los campos en un fieldset con título opcional
        FormField::addPanel('Información del Arrendatario')->setIcon('fa fa-user'),

        // Primera fila de 3 columnas
        FormField::addRow(), // Esto inicia una nueva fila
        TextField::new('ao_nombres', 'Nombres')
            ->setColumns('col-md-4 col-lg-3') // Control de ancho de columna
            ->setFormTypeOption('attr', [
                'class' => 'form-control',
                'placeholder' => 'Ingresa los nombres'
            ]),

        TextField::new('ao_apellidos', 'Apellidos')
            ->setColumns('col-md-4 col-lg-3')
            ->setFormTypeOption('attr', [
                'class' => 'form-control',
                'placeholder' => 'Ingresa los apellidos'
            ]),

        TextField::new('ao_cedula_identidad', 'Cédula identidad')
            ->setColumns('col-md-4 col-lg-3')
            ->setFormTypeOption('attr', [
                'class' => 'form-control',
                'placeholder' => 'Cédula de identidad'
            ]),

        // Segunda fila de 3 columnas
        FormField::addRow(), // Esto inicia una nueva fila
        DateField::new('ao_fecha_nacimiento', 'Fecha de Nacimiento')
            ->setColumns('col-md-4 col-lg-3')
            ->setFormTypeOption('widget', 'single_text')
            ->setFormTypeOption('attr', [
                'class' => 'form-control',
                'placeholder' => 'Selecciona la fecha'
            ]),

        TextField::new('ao_telefono', 'Teléfono')
            ->setColumns('col-md-4 col-lg-3')
            ->setFormTypeOption('attr', [
                'class' => 'form-control',
                'placeholder' => 'Ingresa el teléfono'
            ]),

        TextField::new('ao_tipo', 'Tipo de Arrendatario')
            ->setColumns('col-md-4 col-lg-3')
            ->setFormTypeOption('attr', [
                'class' => 'form-control',
                'placeholder' => 'Tipo (Titular, No titular, etc.)'
            ]),
    ];
}


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $usuario = $this->getUser();
        return $this->arrendatarioRepository->findByUsuario($usuario);
    }
   
    
}
