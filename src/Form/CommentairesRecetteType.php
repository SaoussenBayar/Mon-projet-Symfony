<?php

namespace App\Form;

use App\Entity\CommentairesRecette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentairesRecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Votre commentaire',
            ])
            ->add('note', IntegerType::class, [
                'label' => 'Votre note (1 à 5)',
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentairesRecette::class, // Correction ici
        ]);
    }
}
