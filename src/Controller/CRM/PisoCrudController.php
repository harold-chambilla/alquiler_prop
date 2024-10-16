<?php

namespace App\Controller\CRM;

use App\Entity\Piso;
use App\Entity\Medidor;
use Doctrine\ORM\QueryBuilder;
use App\Repository\PisoRepository;
use App\Repository\ResidenciaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Controller\CRM\ResidenciaCrudController;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PisoCrudController extends AbstractCrudController
{
    private $residenciaRepository;
    private $pisoRepository;

    public function __construct(ResidenciaRepository $residenciaRepository, PisoRepository $pisoRepository)
    {
        $this->residenciaRepository = $residenciaRepository;
        $this->pisoRepository = $pisoRepository;

    }
    public static function getEntityFqcn(): string
    {
        return Piso::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
    
        // Panel de información del piso
        $fields[] = FormField::addPanel('Información del Piso')->setIcon('fa fa-home');
        $fields[] = TextField::new('pi_posicion', 'Posición')
            ->setColumns('col-md-6 col-lg-3')
            ->setFormTypeOption('attr', [
                'placeholder' => 'Ingresa la posición del piso',
                'class' => 'form-control'
            ]);
    
        $fields[] = TextField::new('pi_cuarto', 'Cuarto')
            ->setColumns('col-md-6 col-lg-3')
            ->setFormTypeOption('attr', [
                'placeholder' => 'Ingresa el número de cuarto',
                'class' => 'form-control'
            ]);
    
        $fields[] = TextField::new('pi_zona', 'Zona')
            ->setColumns('col-md-6 col-lg-3')
            ->setFormTypeOption('attr', [
                'placeholder' => 'Ingresa la zona del piso',
                'class' => 'form-control'
            ]);
    
        // Panel de información de residencia
        $fields[] = FormField::addPanel('Información de la Residencia')->setIcon('fa fa-building');
        $fields[] = AssociationField::new('residencia_id', 'Residencia')
            ->setColumns('col-md-6 col-lg-3')
            ->setRequired(true)
            ->setCrudController(ResidenciaCrudController::class)
            ->setFormTypeOption('choice_label', 'res_direccion')
            ->setFormTypeOption('attr', [
                'class' => 'form-control',
                'placeholder' => 'Selecciona la residencia'
            ])
            ->formatValue(function ($value, $entity) {
                return $entity->getResidenciaId()->getResDireccion();
            })
            ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                $usuario = $this->getUser();
                return $queryBuilder
                    ->andWhere('entity.usuario = :usuario')
                    ->setParameter('usuario', $usuario);
            });
    
        // Si estamos en la vista de índice o edición, mostramos el estado
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = ChoiceField::new('pi_estado', 'Estado del Piso')
                ->setColumns('col-md-6 col-lg-3')
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
    

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $usuario = $this->getUser();
        
        // Aquí llamamos al método del repositorio
        return $this->pisoRepository->findByUsuario($usuario);
    }

    public function createEntity(string $entityFqcn)
    {
        $piso = new Piso();
        $piso->setPiEstado(1);
        return $piso;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $piso = $entityInstance;
        $entityManager->persist($piso);        
        $medidor = new Medidor();
        $medidor->setPiso($piso);
        $medidor->setMelTipo('Eléctrico');
        $medidor->setMelMarca('Corpelima');
        $medidor->setMelAño('202'.rand(0, 4));
        $medidor->setMelFechaInstalacion(new DateTime());
        $entityManager->persist($medidor); 
        $entityManager->flush();
        $fechacompra = $medidor->getMelFechaInstalacion();
        $medidor->setMelFechaCompra(date_modify(clone($fechacompra),'-'.rand(1,5).' month'));
        $medidor->setMelCodigo('MED_0'.$medidor->getId());
        $entityManager->flush();
    }

    
}
