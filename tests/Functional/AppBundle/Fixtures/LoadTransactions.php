<?php

namespace Tests\Functional\AppBundle\Fixtures;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTransactions extends Fixture
{
    public const REFERENCE = 'transaction';
    public const AMOUNT = '1.11';

    public function load(ObjectManager $manager)
    {
        $transaction = new Transaction();
        $transaction->setAmount(self::AMOUNT);
        /** @var Customer $customer */
        $customer = $this->getReference(LoadCustomers::REFERENCE);
        $transaction->setCustomer($customer);

        $manager->persist($transaction);
        $manager->flush();

        $this->setReference(self::REFERENCE, $transaction);
    }

    public function getDependencies()
    {
        return [
            LoadCustomers::class,
        ];
    }
}
