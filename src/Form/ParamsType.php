<?php

namespace App\Form;

use App\Entity\Params;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxFilmByBorrow', null, [
                'label' => 'Nombre de films maximum par emprunt'
                ]
            )
            ->add('maxBorrowByUser', null, [
                'label' => 'Nombre d\'emprunts maximum par utilisateur'
                ])
            ->add('borrowLenght', null, [
                'label' => 'Durée d\'un emprunt'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Params::class,
        ]);
    }
}
