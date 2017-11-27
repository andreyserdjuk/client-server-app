<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Transaction;

class TransactionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTotal(): int
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('count(t.id)')
            ->from(Transaction::class, 't')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getByParams(
        Customer $customer = null,
        string $amount = null,
        \DateTime $date = null,
        int $offset = null,
        int $limit = null
    ) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('t')
            ->from(Transaction::class, 't');

        if (null !== $customer) {
            $qb
                ->andWhere('t.customer = :customer')
                ->setParameter('customer', $customer)
            ;
        }

        if (null !== $amount) {
            $qb
                ->andWhere('t.amount = :amount')
                ->setParameter('amount', $amount)
            ;
        }

        if (null !== $date) {
            $qb
                ->andWhere('t.date = :date')
                ->setParameter('date', $date)
            ;
        }

        if (null !== $offset) {
            $qb
                ->setFirstResult($offset)
            ;
        }

        if (null !== $limit) {
            $qb
                ->setMaxResults($limit)
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
