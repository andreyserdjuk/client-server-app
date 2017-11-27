<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Transaction;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDemoData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        for ($c=1; $c < 11; ++$c) {
            $customer = new Customer();
            $customer->setName('customer-'.$c);
            $customer->setCnp(true);
            $manager->persist($customer);
            $manager->flush();

            for ($t=1; $t < 11; ++$t) {
                $transaction = (new Transaction())
                    ->setCustomer($customer)
                    ->setAmount($c)
                    ->setDate(new \DateTime());
                $manager->persist($transaction);
            }
        }

        $manager->flush();
    }
}
