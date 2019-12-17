<?php
/**
 * Used to confirm Aspect Mock and other requirements are met
 */

namespace CirrusIdentity\SSP\Test;

use AspectMock\Test as test;

class SanityChecker
{
    /**
     * Checks that AspectMock is configured and can override HTTP Util methods
     * @return \AspectMock\Proxy\ClassProxy|\AspectMock\Proxy\InstanceProxy|\AspectMock\Proxy\Verifier
     * @throws \Exception
     */
    public static function confirmAspectMockConfigured()
    {
        // Ensure mocks are configured for SSP classes
        $httpDouble = test::double('SimpleSAML\Utils\HTTP', [
            'getAcceptLanguage' => ['some-lang']
        ]);

        if (['some-lang'] !== \SimpleSAML\Utils\HTTP::getAcceptLanguage()) {
            throw new \Exception("Aspect mock does not seem to be configured");
        }
        // You can also validate the that a method was called.
        // $httpDouble->verifyInvokedOnce('getAcceptLanguage');
        return $httpDouble;
    }
}
