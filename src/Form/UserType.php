<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function __construct(
        private Security $security,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On ajoute un eventListener
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $userToModifed = $event->getData();
            $userConnected = $this->security->getUser();

            if ($userToModifed === $userConnected) {
                $form
                    ->add('firstName', TextType::class, [
                        'label' => 'Prénom:',
                        'attr' => [
                            'placeholder' => 'John',
                        ],
                        'required' => true,
                    ])
                    ->add('lastName', TextType::class, [
                        'label' => 'Nom:',
                        'attr' => [
                            'placeholder' => 'Doe',
                        ],
                        'required' => true,
                    ])
                    ->add('email', EmailType::class, [
                        'label' => 'Email:',
                        'attr' => [
                            'placeholder' => 'john@exemple.com',
                        ],
                        'required' => true,
                    ]);
            }

            if ($this->security->isGranted('ROLE_ADMIN')) {
                $form
                    ->add('roles', ChoiceType::class, [
                        'label' => 'Roles:',
                        'choices' => [
                            'Utilisateur' => 'ROLE_USER',
                            'Éditeur' => 'ROLE_EDITOR',
                            'Administrateur' => 'ROLE_ADMIN',
                        ],
                        'expanded' => false,
                        'multiple' => true,
                        'autocomplete' => true,
                    ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
