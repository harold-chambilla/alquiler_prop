<?php

namespace App\Controller\CRM;

use DateTime;
use App\Entity\Contrato;
use App\Entity\Arrendatario;
use Doctrine\ORM\QueryBuilder;
use App\Repository\ContratoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\CRM\PisoCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Routing\RouterInterface;

class ContratoCrudController extends AbstractCrudController
{
    private $contratoRepository;
    private $router;
    private $entitymanager;

    public function __construct(ContratoRepository $contratoRepository, EntityManagerInterface $entitymanager, RouterInterface $routerInterface)
    {
        $this->contratoRepository = $contratoRepository;
        $this->entitymanager = $entitymanager;
        $this->router = $routerInterface;
    }

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

    // Información del contrato
    $fields[] = FormField::addPanel('Información del Contrato')->setIcon('fa fa-file-contract');
    $fields[] = DateField::new('co_fecha_ingreso', 'Fecha de Ingreso')
        ->setColumns('col-md-6 col-lg-3')
        ->setRequired(true);

    $fields[] = AssociationField::new('piso_id', 'Piso')
        ->setColumns('col-md-6 col-lg-6')
        ->setRequired(true)
        ->setCrudController(PisoCrudController::class)
        ->setFormTypeOption('choice_label', function ($piso) {
            $residencia = $piso->getResidenciaId();
            return sprintf(
                'Posición: %s, Cuarto: %s, Zona: %s - Residencia: %s',
                $piso->getPiPosicion(),
                $piso->getPiCuarto(),
                $piso->getPiZona(),
                $residencia ? $residencia->getResDireccion() : 'Sin residencia asignada'
            );
        })
        ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
            $usuario = $this->getUser();
            return $queryBuilder
                ->join('entity.residencia_id', 'r')
                ->andWhere('r.usuario = :usuario')
                ->setParameter('usuario', $usuario);
        });

    $fields[] = DateField::new('co_fecha_vencimiento', 'Fecha de Vencimiento')
        ->setColumns('col-md-6 col-lg-3')
        ->setRequired(true);

    // Información del arrendatario
    if ($pageName !== Crud::PAGE_INDEX) {
        $fields[] = FormField::addPanel('Información del Arrendatario')->setIcon('fa fa-user');

        $fields[] = TextField::new('arrendatario_id.ao_nombres', 'Nombres del Arrendatario')
            ->setColumns('col-md-6 col-lg-3')
            ->setFormTypeOption('attr', ['placeholder' => 'Ingresa los nombres']);

        $fields[] = TextField::new('arrendatario_id.ao_apellidos', 'Apellidos del Arrendatario')
            ->setColumns('col-md-6 col-lg-3')
            ->setFormTypeOption('attr', ['placeholder' => 'Ingresa los apellidos']);

        $fields[] = TextField::new('arrendatario_id.ao_telefono', 'Teléfono del Arrendatario')
            ->setColumns('col-md-6 col-lg-3')
            ->setFormTypeOption('attr', ['placeholder' => 'Ingresa el teléfono']);

        $fields[] = ChoiceField::new('arrendatario_id.ao_tipo', 'Tipo de Arrendatario')
            ->setColumns('col-md-6 col-lg-3')
            ->setChoices([
                'Titular' => 'titular',
                'No titular' => 'no titular',
            ])
            ->setRequired(true);

        $fields[] = TextField::new('arrendatario_id.ao_cedula_identidad', 'Cédula de Identidad')
            ->setColumns('col-md-6 col-lg-3')
            ->setFormTypeOption('attr', ['placeholder' => 'Cédula de Identidad'])
            ->setRequired(true);

        $fields[] = DateField::new('arrendatario_id.ao_fecha_nacimiento', 'Fecha de Nacimiento del Arrendatario')
            ->setColumns('col-md-6 col-lg-3')
            ->setRequired(true)
            ->setFormTypeOption('widget', 'single_text');
    }

    // Información económica del contrato
    $fields[] = FormField::addPanel('Detalles Económicos')->setIcon('fa fa-money-bill');
    $fields[] = MoneyField::new('co_alquiler_mensual', 'Alquiler Mensual')
        ->setColumns('col-md-6 col-lg-3')
        ->setCurrency('PEN')
        ->setStoredAsCents(false)
        ->setNumDecimals(2)
        ->setRequired(true);

    $fields[] = MoneyField::new('co_agua', 'Agua')
        ->setColumns('col-md-6 col-lg-3')
        ->setCurrency('PEN')
        ->setStoredAsCents(false)
        ->setNumDecimals(2)
        ->setRequired(true);

    // Estado del contrato
    if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_EDIT) {
        $fields[] = ChoiceField::new('co_estado', 'Estado del Contrato')
            ->setColumns('col-md-6 col-lg-3')
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
}

    public function configureActions(Actions $actions): Actions
    {
        $createRecibo = Action::new('createRecibo', 'Crear Recibo')
            ->linkToCrudAction('redirectToCreateRecibo')
            ->setIcon('fa fa-file-invoice');

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
            ->add(Crud::PAGE_INDEX, $createRecibo)
            ->add(Crud::PAGE_DETAIL, $createRecibo)
            ->disable(Action::DELETE);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $usuario = $this->getUser();
        return $this->contratoRepository->findByUsuario($usuario);
    }

    public function createEntity(string $entityFqcn)
    {
        $contrato = new Contrato();
        date_default_timezone_set('America/Lima');
        $fechaactual = new DateTime();
        $contrato->setCoFechaActual($fechaactual);
        // Inicializamos un nuevo arrendatario
        $arrendatario = new Arrendatario();
        $contrato->setArrendatarioId($arrendatario);
        $contrato->setCoEstado(1);
        // $this->entitymanager->persist($contrato);
        // $this->entitymanager->flush();

        // $contrato->setCoFechaVencimiento($contrato->getCoFechaIngreso());
        // $this->entitymanager->flush();
        return $contrato;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
	{
        if (!$entityInstance instanceof Contrato) {
            return;
        }

        // Obtener la fecha de ingreso
        $fechaIngreso = $entityInstance->getCoFechaIngreso();
        
        // Verificar que la fecha de ingreso no sea nula
        if ($fechaIngreso) {
            $fechaVencimiento = date_modify(clone $entityInstance->getCoFechaIngreso(), '+1 month');
            $entityInstance->setCoFechaVencimiento($fechaVencimiento);
        }

        // Persistir la entidad
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function redirectToCreateRecibo(AdminContext $context): RedirectResponse
    {
        // Obtenemos el contrato actual
        $contrato = $context->getEntity()->getInstance();

        // Redirigimos a la página de creación de recibo, pasando el contrato como parámetro
        $url = $this->router->generate('app_admin', [
            'crudControllerFqcn' => ReciboCrudController::class,
            'crudAction' => 'new',
            'contrato_id' => $contrato->getId(),
        ]);

        return $this->redirect($url);
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
