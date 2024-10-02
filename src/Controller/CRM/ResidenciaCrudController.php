<?php

namespace App\Controller\CRM;

use App\Entity\Residencia;
use Doctrine\ORM\QueryBuilder;
use App\Repository\ResidenciaRepository;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ResidenciaCrudController extends AbstractCrudController
{
    private $residenciaRepository;
    private $security;

    public function __construct(ResidenciaRepository $residenciaRepository, Security $security)
    {
        $this->residenciaRepository = $residenciaRepository;
        $this->security = $security;
    }
    public static function getEntityFqcn(): string
    {
        return Residencia::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        // Panel para la información de la dirección
        FormField::addPanel('Información de la Residencia')->setIcon('fa fa-map-marker-alt'),
        
        // Campo de dirección con clases y placeholder personalizados
        TextField::new('res_direccion', 'Dirección')
            ->setColumns('col-md-6 col-lg-4')  // Controla la disposición del campo en la fila
            ->setFormTypeOption('attr', [
                'placeholder' => 'Ingresa la dirección de la residencia',
                'class' => 'form-control'
            ]),
    ];
}

    public function createEntity(string $entityFqcn)
    {
        $residencia = new Residencia;
        $usuario = $this->getUser();
        $residencia->setUsuario($usuario);
        return $residencia;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $usuario = $this->getUser();
        
        // Aquí llamamos al método del repositorio
        return $this->residenciaRepository->findByUsuario($usuario);
    }
}
