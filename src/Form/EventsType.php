<?php


namespace App\Form;
use App\Entity\Events;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;


class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ["attr"=> ["class" => "form-control mb-3" , "placeholder"=> "Type your events Name!!"]])
            ->add('Date', DateTimeType::class, ["attr"=> ["class" => "form-control mb-3"]])
            ->add('description', TextType::class, ["attr"=> ["class" => "form-control mb-3" , "placeholder"=> "Type Description !!"]])
            ->add('capacity', IntegerType::class, ["attr"=> ["class" => "form-control mb-3"]])
            ->add('email', EmailType::class, ["attr"=> ["class" => "form-control mb-3" , "placeholder"=> "Type your Email"]])
            ->add('address',TextType::class, ["attr"=> ["class" => "form-control mb-3" , "placeholder"=> "Type your events Address"]])
            ->add('url', UrlType::class, ["attr"=> ["class" => "form-control mb-3"]])
            ->add('image', FileType::class, [
                'label' => 'image (png, jpg, web)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/web',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],

                "attr"=> ["class" => "form-control mb-3"]
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'music' => "music",
                    'sport' => "sport",
                    'movie' => "movie",
                    'theater' => "theater",
                ],
                   "attr"=> ["class" => "form-control mb-3"]
                
            ])
            
            ->add('Phone', NumberType::class, ["attr"=> ["class" => "form-control mb-3" , "placeholder"=> "Type your Phone Number!!"]])
            ->add('Create', SubmitType::class, ["attr"=> ["class" => "form-control btn btn-dark"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}