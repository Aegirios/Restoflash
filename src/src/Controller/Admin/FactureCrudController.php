<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FactureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Facture::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            MoneyField::new('amount')->setCurrency('EUR'),
            DateTimeField::new('emissionDate'),
            DateTimeField::new('echanceDate'),
            ChoiceField::new('statut')
                ->autocomplete(true)
                ->setFormTypeOptions([
                    'multiple' => false,
                    'by_reference' => true,
                ])
                ->setChoices(['pending', 'finished', 'validation'])
                ->setLabel('statut'),

            TextEditorField::new('comment'),
        ];
    }
}
