<?php

namespace App\Form\creationSondage;

use App\Entity\Question;
use App\Entity\TypeQuestion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class QuestionEmbededForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule',TextType::class)

            ->add('TypeQuestion',EntityType::class,[
                'class'=>TypeQuestion::class,
                'choice_label'=>'intituleType',
            ])
            ->add('imageQuestion',FileType::class,[
                'label' =>'Choisir une image',
                'mapped' => false,
                'required' => false,
            ])
            ->add('Reponses',CollectionType::class, [
            'entry_type' => ReponseEmbededForm::class,
            'allow_delete' => true,
            'by_reference' => false,
            'allow_add' => true,
            'entry_options' => ['label' => false],
            ]);
            //->add('sondage');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
