<?php

namespace Tests\Functional;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

trait LoadFixturesTrait
{
    /** @var  ReferenceRepository */
    protected $referenceRepo;

    protected function loadFixtures(array $fixturesData)
    {
        $loader = new ContainerAwareLoader($this->getContainer());
        foreach ($fixturesData as $fixtureData) {
            if (is_dir($fixtureData)) {
                $loader->loadFromDirectory($fixtureData);
            } elseif (is_file($fixtureData)) {
                $loader->loadFromFile($fixtureData);
            } elseif (class_exists($fixtureData)) {
                $reflector = new \ReflectionClass($fixtureData);
                $loader->loadFromFile($reflector->getFileName());
            }
        }

        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new \InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $fixturesData))
            );
        }

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $executor = new ORMExecutor($em);
        $executor->execute($fixtures, true);
        $this->referenceRepo = $executor->getReferenceRepository();
    }

    protected function getReference($ref)
    {
        return $this->referenceRepo->getReference($ref);
    }

    abstract protected static function getContainer();
}
