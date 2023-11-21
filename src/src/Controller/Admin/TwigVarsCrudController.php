<?php

namespace App\Controller\Admin;

use App\Entity\TwigVars;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TwigVarsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TwigVars::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->hideOnForm(),
            TextField::new('controller')->hideOnForm(),
            TextEditorField::new('value'),
        ];
    }

}
