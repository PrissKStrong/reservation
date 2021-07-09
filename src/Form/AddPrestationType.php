<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Prestations;
use App\Repository\CategoriesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AddPrestationType extends AbstractType
{
    private $categoryRepository;

    private $security;

    public function __construct(
        CategoriesRepository $categoryRepository,
        Security $security
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la prestation'
            ])
            ->add('image', TextType::class, [
                'label' => 'Insérez votre image'
            ])
            ->add('prestaTime', NumberType::class, [
                'label' => 'Durée en minutes'
            ])
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Selectionnez une catégorie ',
                'choices' => $this->categoryRepository->findCategoryByUser($this->security->getUser())
            ])

           ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prestations::class,
        ]);
    }
}
