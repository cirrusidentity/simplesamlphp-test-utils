<?php

namespace CirrusIdentity\SSP\Test;

use CirrusIdentity\SSP\Test\Capture\RedirectException;
use CirrusIdentity\SSP\Test\Capture\TrustedRedirectException;
use CirrusIdentity\SSP\Test\Capture\UntrustedRedirectException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Utils\ClearableState;
use SimpleSAML\Utils\HTTP;

class MockHttpBuilder
{
    /**
     * @var MockObject|HTTP
     */
    private MockObject $http;


    public function __construct(MockObject $http)
    {
        $this->http = $http;
    }

    /**
     * @param TestCase $testCase
     * @return MockObject&HTTP
     */
    public static function createHttpMockFromTestCase(TestCase $testCase): MockObject
    {
        $http = $testCase->getMockBuilder(HTTP::class)
            ->getMock();
        return (new MockHttpBuilder($http))
            ->throwOnRedirectTrustedURL()
            ->throwOnRedirectUntrustedURL()
            ->passThroughAddURLParameters()
            ->getHttp();
    }

    /**
     * @return MockObject&HTTP
     */
    public function getHttp(): MockObject
    {
        return $this->http;
    }


    /**
     * Add URL parameters can be run on the real http object to make testing of
     * url generation easier.
     * @return MockHttpBuilder
     */
    public function passThroughAddURLParameters(): MockHttpBuilder
    {
        $this->http->method('addURLParameters')
            ->will(
                TestCase::returnCallback(
                    function (string $url, array $parameters): string {
                        return (new HTTP())->addURLParameters($url, $parameters);
                    }
                )
            );

        return $this;
    }

    public function throwOnRedirectTrustedURL($includeArgsInMessage = false): self
    {
        $this->http->method('redirectTrustedURL')
            ->will(
                TestCase::returnCallback(function (...$args) use ($includeArgsInMessage) {
                    $msg = 'redirectTrustedURL';
                    if ($includeArgsInMessage) {
                        $msg .= ': ' . var_export($args, true);
                    }
                    throw new TrustedRedirectException($msg, $args);
                }
                )
            );
        return $this;
    }

    public function throwOnRedirectUntrustedURL(): self
    {
        $this->http->method('redirectUntrustedURL')
            ->will(
                TestCase::returnCallback(function (...$args) {
                    throw new UntrustedRedirectException('redirectUntrustedURL', $args);
                }
                )
            );
        return $this;
    }
}
