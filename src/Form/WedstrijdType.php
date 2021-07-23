<?php

namespace App\Form;

use App\Entity\Speler;
use App\Entity\Wedstrijd;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WedstrijdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('spelerHalf', EntityType::class, [
                'class' => Speler::class,
                'choice_label' => 'naam',
            ])
            ->add('spelerHeel', EntityType::class, [
                'class' => Speler::class,
                'choice_label' => 'naam',

            ])
            ->add('winnaar', EntityType::class, [
                'class' => Speler::class,
                'choice_label' => 'naam',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wedstrijd::class,
        ]);
    }
}
