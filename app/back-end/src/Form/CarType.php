<?php

namespace App\Form;

use App\Entity\AddService;
use App\Entity\Car;
use App\Entity\CarClass;
use App\Entity\StationService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('car_vin')
            ->add('car_make')
            ->add('car_model')
            ->add('car_date_of_issue', null, [
                'widget' => 'single_text',
            ])
            ->add('car_state_number')
            ->add('cls_title')
            ->add('stn_id')
            ->add('car_body_type')
            ->add('car_color')
            ->add('car_status')
            ->add('car_gearbox_type')
            ->add('car_img')
            ->add('station_service', EntityType::class, [
                'class' => StationService::class,
                'choice_label' => 'id',
            ])
            ->add('car_class', EntityType::class, [
                'class' => CarClass::class,
                'choice_label' => 'id',
            ])
            ->add('add_services', EntityType::class, [
                'class' => AddService::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
