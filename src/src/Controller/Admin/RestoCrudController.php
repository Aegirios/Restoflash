<?php

namespace App\Controller\Admin;

use App\Entity\Resto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\LanguageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RestoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Resto::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            LanguageField::new('lang'),
            TextField::new('slogan'),
            TextField::new('adress'),
            ImageField::new('logo')->setUploadDir('dist' . DIRECTORY_SEPARATOR . 'uploads')->setBasePath('dist' . DIRECTORY_SEPARATOR . 'uploads'),
            TelephoneField::new('telephone'),
            EmailField::new('contactMail'),
            TextField::new('gps')->hideOnForm(),
            BooleanField::new('isReservationPossible'),
            TextEditorField::new('cgu'),
            TextEditorField::new('confidentialityPolicy'),
            TextEditorField::new('reservationPolicy'),
        ];
    }

}
