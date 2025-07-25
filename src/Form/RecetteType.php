<?php
namespace App\Form;

use App\Entity\Recette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre de la recette'
            ])
            ->add('ingredients', TextareaType::class, [
                'label' => 'Ingrédients (séparés par des virgules)'
            ])
            ->add('detail', TextareaType::class, [
                'label' => 'Détails de la recette'
            ])
            ->add('age_recommende', TextType::class, [
                'label' => 'Âge recommandé'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de la recette',
                'required' => false,
                'mapped' => false, 
                'attr' => ['accept' => 'image/*']

            ])
            ->add('tempsPrep', IntegerType::class, [
                'label' => 'Temps de préparation (en minutes)'
            ]);
            

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
