<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categories;
use App\Form\ArticleImageType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre de l\'article',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'article',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Description de l\'article',
                    'rows' => 5,
                ],
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ArticleImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
            ->add('categories', EntityType::class, [
                'label' => 'Catégories:',
                'class' => Categories::class,
                'choice_label' => 'title',
                'expanded' => false,
                'multiple' => true,
                'autocomplete' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'translation_domain' => 'form',
        ]);
    }
}
