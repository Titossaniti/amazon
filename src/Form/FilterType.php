<?php

// src/Form/FilterType.php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom du produit'
                ],
            ]);
        if ($options['include_category']) {
            $builder->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => false,
                'placeholder' => 'Catégorie',
                'attr' => [
                    'class' => 'placeholder-text',
                ],
            ]);
        }
        if ($options['include_user']) {
            $builder->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'required' => false,
                'label' => false,
                'placeholder' => 'Vendeur',
                'attr' => [
                    'class' => 'placeholder-text',
                ],
            ]);
        }

        $builder
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
            ->add('reset', ButtonType::class, [
                'label' => 'Réinitialiser',
                'attr' => [
                    'onclick' => 'resetForm(event)'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'include_user' => true,
            'include_category' => true
        ]);
    }
}
