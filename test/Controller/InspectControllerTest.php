<?php

namespace PommProjectTest\PommModule\Controller;

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use PommProjectTest\PommModule\Bootstrap;

class InspectConfigControllerTest extends AbstractConsoleControllerTestCase
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
    public function testInspectConfig()
    {
        // TODO
    }

    public function testInspectDatabase()
    {
        // TODO
    }

    public function testInspectSchema()
    {
        // TODO
    }

    public function testInspectRelation()
    {
        // TODO
    }
}
