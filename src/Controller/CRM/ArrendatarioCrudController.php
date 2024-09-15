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
            TextField::new('ao_nombres', 'Nombres'),
            TextField::new('ao_apellidos', 'Apellidos'),
            TextField::new('ao_telefono', 'Telefono'),
            TextField::new('ao_tipo', 'Tipo'),
            TextField::new('ao_cedula_identidad', 'Cédula identidad'),
            DateField::new('ao_fecha_nacimiento', 'Fech. Nacimiento'),
            
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
