<?php

namespace Tests\Functional\AppBundle\Fixtures;

use AppBundle\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCustomers extends Fixture
{
    public const REFERENCE = 'customer';

    public function load(ObjectManager $manager)
    {
        $customer = new Customer();
        $customer->setName('John Doe');
        $customer->setCnp(true);

        $manager->persist($customer);
        $manager->flush();

        $this->setReference(self::REFERENCE, $customer);
    }
}
