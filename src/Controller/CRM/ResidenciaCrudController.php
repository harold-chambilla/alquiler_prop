<?php

namespace App\Controller\CRM;

use App\Entity\Residencia;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ResidenciaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Residencia::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('res_direccion', 'Dirección'),
            AssociationField::new('contratos', 'Contratos'),
            AssociationField::new('pisos', 'Pisos'),
        ];
    }
}
