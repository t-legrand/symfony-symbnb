<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{
    /**
     * Permet d'avoir la configuration de base d'un champ !
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    public function getConfiguration($label, $placeholder, $options = []) {
    return array_merge([
    'label' => $label,
    'attr' => [
        'placeholder' => $placeholder
        ]
    ], $options);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre","Donnez un titre à votre annonce"))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web","Tapez l'adresse web (automatique)", ['required' => false]))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale","Donnez l'adresse d'une image représentant votre propriété"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction","Donnez une description globale de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée","Tapez une descriion qui donne envie de venir chez vous !"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres","Donnez le nombre de chambres de votre propriété"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit","Indiquez le prix d'une nuit dans votre propriété"))
            ->add('images', CollectionType::class, ['entry_type' => ImageType::class, 'allow_add' => true, 'allow_delete' => true]) ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
