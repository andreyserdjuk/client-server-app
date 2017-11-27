<?php

namespace Tests\Functional\AppBundle\Repository;

use AppBundle\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Functional\AppBundle\Fixtures\LoadTransactions;
use Tests\Functional\BaseFuncTestCase;

class TransactionRepositoryTest extends BaseFuncTestCase
{
    public function setUp()
    {
        self::initClient();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get('doctrine.orm.entity_manager');
        $em->beginTransaction();

        $this->loadFixtures([
            LoadTransactions::class,
        ]);
    }

    public function testGetByParams()
    {
        /** @var Transaction $transaction */
        $transaction = $this->getReference(LoadTransactions::REFERENCE);

        $transactions = $this
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Transaction::class)
            ->getByParams(
                $transaction->getCustomer(),
                LoadTransactions::AMOUNT,
                $transaction->getDate(),
                0,
                1
            )
        ;

        $this->assertCount(1, $transactions);
        $this->assertInstanceOf(Transaction::class, $transactions[0]);
        $this->assertEquals($transaction->getCustomer(), $transactions[0]->getCustomer());
    }

    public function tearDown()
    {
        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get('doctrine.orm.entity_manager');
        $em->rollback();
    }
}
