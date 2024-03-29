<?php

namespace App\Form\repondreSondage;

use App\Entity\Sondage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RepondreSondageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $sondage = $options['sondage'];

        foreach ($sondage->getQuestions() as $question) {
            $choices = [];
            foreach ($question->getReponses() as $reponse) {
                $choices[$reponse->getLaReponse()] = $reponse->getId();
            }

            $builder->add('question_' . $question->getId(), ChoiceType::class, [
                'label' => $question->getIntitule(),
                'choices' => $choices,
                'expanded' =>   $question->getTypeQuestion()->isExpanded(),
                'multiple' =>  $question->getTypeQuestion()->isMultiple(),
                'required' => true,
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Envoyer',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('sondage');
        $resolver->setAllowedTypes('sondage', Sondage::class);
    }

    /*private function getFieldType($question): string
    {
        $type = $question->getTypeQuestion()->getIntituleType();

        return match ($type) {
            'choices' => ChoiceType::class,
            'unique' => ChoiceType::class,
            default => throw new \InvalidArgumentException('Type de question invalide: ' . $type),
        };
    }*/
}
