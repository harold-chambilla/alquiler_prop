<?php

namespace App\Form;

use App\Entity\ConceptoPago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConceptoPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cop_nombre', TextType::class, [
                'label' => 'Nombre',
            ])
            ->add('cop_descripcion', TextType::class, [
                'label' => 'Descripción',
            ])
            ->add('cop_precio', NumberType::class, [
                'label' => 'Precio',
            ])
            ->add('cop_estado', HiddenType::class, [
                'data' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConceptoPago::class,
        ]);
    }
}
