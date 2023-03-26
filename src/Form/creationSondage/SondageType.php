<?php

namespace App\Form\creationSondage;

use App\Entity\CategorieSondage;
use App\Entity\Sondage;
use App\Entity\TypeQuestion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SondageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule')
            ->add('description',TextareaType::class)
            ->add('categorieSondage',EntityType::class,[
                'class'=>CategorieSondage::class,
                'choice_label'=>'nomCategorie',
            ])
            ->add('imageCouverture',FileType::class,[
                'label' =>'Choisir une image de couverture',
                'mapped' => false,
                'required' => false,
            ])
            ->add('dateLancement',DateType::class,[
                'widget'=>'single_text'
            ])
            ->add('dateFin',DateType::class,[
                'widget'=>'single_text'
            ])
            ->add('Questions',CollectionType::class, [
                'entry_type' => QuestionEmbededForm::class,
                'allow_delete' => true,
                'by_reference' => false,
                'allow_add' => true,
                'entry_options' => ['label' => false],
            ])
            ->add('Enregistrer',SubmitType::class,[
                'attr'=> [
                    'class'=> 'button-enregister'
                ]
            ]);
//            ->addEventListener(FormEvents::PRE_SUBMIT,function ($event){
//                $data = $event->getData();
//                $data['Questions'] = array_values($data['Questions']);
//                $event->setData($data);}
//            );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sondage::class,
        ]);
    }

}
