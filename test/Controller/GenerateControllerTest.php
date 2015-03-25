<?php

namespace PommProjectTest\PommModule\Controller;

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use PommProjectTest\PommModule\Bootstrap;
use PommProject\Foundation\Pomm as PommService;

class InspectDatabaseControllerTest extends AbstractConsoleControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        Bootstrap::init();
        $this->setApplicationConfig(
            include 'test/test.config.php'
        );
        parent::setUp();
    }

    /**
     * Check console
     * 
     * @return void
     */
    public function testGenerateModel()
    {
        // TODO
    }

    public function testGenerateStructure()
    {
        // TODO
    }

    public function testGenerateEntity()
    {
        // TODO
    }

    public function testGenerateRelation()
    {
        // TODO
    }

    public function testGenerateSchemaAll()
    {
        // TODO
    }

    public function testGenerateDatabaseAll()
    {
        // TODO
    }
}
