<?php

namespace AppBundle\Form\Model;

use AppBundle\Entity\Customer;

class TransactionFilters
{
    /** @var Customer */
    private $customer;

    /** @var  string */
    private $amount;

    /** @var  \DateTime */
    private $date;

    /** @var  int */
    private $offset;

    /** @var  int */
    private $limit;

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount)
    {
        $this->amount = $amount;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date = null)
    {
        $this->date = $date;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(int $offset)
    {
        $this->offset = $offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;
    }
}
