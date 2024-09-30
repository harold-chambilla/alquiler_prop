<?php

namespace App\Form;

use App\Entity\ConceptoPago;
use App\Entity\Recibo;
use App\Entity\ReciboConceptoPago;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReciboConceptoPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rcp_fecha_digitacion', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Fecha',
                'required' => true,
            ])
            ->add('concepto_pago_id', ConceptoPagoType::class, [
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReciboConceptoPago::class,
        ]);
    }
}
