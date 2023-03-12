<?php

namespace App\Form;

use App\Entity\Reponse;
use AppBundle\Form\QuestionEmbededForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AnswerEmbededForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [
                'texte 1' => 'valeur1',
                'texte 2' => 'valeur2'
            ];
        $builder->
        $builder
            ->add('radio', ChoiceType::class, [
                'value' => 'valeur1', // cochée par défaut
                'choices' => $choices,
                'expanded' => false,  // => boutons
                'label' => 'MonLabel',
            ]);

            //->add('question');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           // 'data_class' => Reponse::class,
        ]);
    }
}
