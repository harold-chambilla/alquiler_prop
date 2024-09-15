<?php

namespace App\Controller\CRM;

use App\Entity\Arrendatario;
use App\Entity\Contrato;
use App\Entity\Piso;
use App\Entity\Residencia;
use App\Repository\ArrendatarioRepository;
use App\Repository\ContratoRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private ArrendatarioRepository $arrendatarioRepository, private ContratoRepository $contratoRepository){ }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(ContratoCrudController::class)->generateUrl());
        
        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        $user = $this->getUser();
        $arrendatarios = $this->arrendatarioRepository->findByUsuario($user);

        return $this->render('crm/dashboard.html.twig', [
            "arrendatarios" => $this->arrendatarioRepository->findAll(),
            "contratos" => $this->contratoRepository->findAll()
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Alquiler Prop');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Contrato', 'fa fa-file-text', Contrato::class);
        yield MenuItem::linkToCrud('Arrendatario', 'fa fa-user', Arrendatario::class);
        yield MenuItem::linkToCrud('Residencia', 'fa fa-leaf', Residencia::class);
        yield MenuItem::linkToCrud('Piso', 'fa fa-building', Piso::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
