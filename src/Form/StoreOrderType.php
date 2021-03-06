<?php

namespace App\Form;

use App\Controller\StoreController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class StoreOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'required' => false,
            ])
        ;

        foreach (array_keys(StoreController::ARTICLES) as $key) {
            $builder->add("quantity_$key", IntegerType::class, [
                'required' => false,
                'data' => 0,
                'constraints' => [
                    new GreaterThanOrEqual(0),
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
