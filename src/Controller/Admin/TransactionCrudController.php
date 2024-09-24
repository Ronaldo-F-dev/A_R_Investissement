<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Transaction;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TransactionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('refTrans'),
            TextField::new('type'),
            NumberField::new('montant'),
            DateTimeField::new('createdAt'),
            IntegerField::new('statut'),
            AssociationField::new("user")->setCrudController(UserCrudController::class),
            AssociationField::new("investissement")->setCrudController(InvestissementCrudController::class),
        ];
    }

}
