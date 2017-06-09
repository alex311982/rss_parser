<?php

namespace Gubarev\Bundle\FeedBundle\Tests\Repository;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Tools\SchemaTool;
use Gubarev\Bundle\FeedBundle\Repository\Interfaces\RepositoryInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractRepositoryTestCase extends TestCase
{
    /**
     * @var AppKernel
     */
    protected $kernel;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;
    /**
     * @var []
     */
    protected $metadata;
    /**
     * @var SchemaTool
     */
    protected $tool;
    /**
     * @var RepositoryInterface
     */
    protected $repo;

    public function setUp()
    {
        $loader = require __DIR__ . '/../../vendor/autoload.php';
        AnnotationRegistry::registerLoader(array($loader, "loadClass"));

        // Boot the AppKernel in the test environment and with the debug.
        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();

        // Store the container and the entity manager in test case properties
        $this->container = $this->kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getEntityManager();

        // Build the schema for sqlite
        $this->generateSchema();

        $this->setupRepository();

        parent::setUp();
    }

    public function tearDown()
    {
        $this->tool->dropSchema($this->metadata);
        // Shutdown the kernel.
        $this->kernel->shutdown();

        parent::tearDown();
    }

    protected function generateSchema()
    {
        // Get the metadata of the application to create the schema.
        $this->metadata = $this->getMetadata();

        if ( ! empty($this->metadata)) {
            // Create SchemaTool
            $this->tool = new SchemaTool($this->em);
            $this->tool->dropSchema($this->metadata);
            $this->tool->createSchema($this->metadata);
        } else {
            throw new \Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }

    /**
     * Overwrite this method to get specific metadata.
     *
     * @return array
     */
    protected function getMetadata()
    {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }

    abstract public function setupRepository();
}
