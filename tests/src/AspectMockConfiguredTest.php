<?php

namespace CirrusIdentity\SSP\Test;

use AspectMock\Test as test;

class AspectMockConfigured extends \PHPUnit_Framework_TestCase
{

    protected function tearDown()
    {
        test::clean(); // remove all registered test doubles
    }

    /**
     * Test to confirm AspectMock is configured correctly. phpunit bootstrap.php has the configuration for AspectMock
     * and that is where you can tell it which classes to do its AOP magic on.
     */
    public function testAspectMockConfigured()
    {
        // Ensure mocks are configured for SSP classes
        $httpDouble = SanityChecker::confirmAspectMockConfigured();

        // We can also validate the that a method was called.
        $httpDouble->verifyInvokedOnce('getAcceptLanguage');
    }
}