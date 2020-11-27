<?php


namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $currentDate = new \DateTime();
        $currentYear = $currentDate->format('Y');
        $year = $currentYear - 20;

        $builder->add('firstName');
        $builder->add('lastName', TextType::class, array('required'=> true));
        $builder->add('birthDate', BirthdayType::class, array(
            'format' =>'ddMyyyy',
            'widget' => 'choice',
            'data' => new \DateTime($year.'-01-01')
        ));
        $builder->add('email',EmailType::class);
        $builder->add('password', PasswordType::class);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}