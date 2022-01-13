<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalculatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('articles', CollectionType::class, [
                'entry_type' => ArticleType::class,
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ])
            ->add('name', null, ['label' => 'Nom'])
            ->add('reference', null, ['label' => 'Référence'])
            ->add('width', null, ['label' => 'Laize (cm)'])
            ->add('withdrawLength', null, ['label' => 'Retrait en longueur (%)'])
            ->add('withdrawWidth', null, ['label' => 'Retrait en largeur (%)'])
            ->add('length', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
