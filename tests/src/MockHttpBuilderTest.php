<?php

namespace CirrusIdentity\SSP\Test;

use CirrusIdentity\SSP\Test\Capture\RedirectException;
use CirrusIdentity\SSP\Test\Capture\TrustedRedirectException;
use CirrusIdentity\SSP\Test\Capture\UntrustedRedirectException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Utils\HTTP;


/**
 *
 * Tests mock of various SSP HTTP util static calls
 */
class MockHttpBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        $this->mockHttp = MockHttpBuilder::createHttpMockFromTestCase($this);
    }


    public function testTrustedRedirect()
    {


        // Enable throwing an exception when redirects would normally be called.
        $params = [
            'state' => '1234'
        ];
        try {
            $this->mockHttp->redirectTrustedURL('http://my.url.com', $params);
            $this->fail('Exception expected');
        } catch (TrustedRedirectException $e) {
            $this->assertEquals('redirectTrustedURL', $e->getMessage());
            $this->assertEquals('http://my.url.com', $e->getUrl());
            $this->assertEquals($params, $e->getParams());
        }

        // Confirm it can be called multiple times
        try {
            $this->mockHttp->redirectTrustedURL('http://other.url.com');
            $this->fail('Exception expected');
        } catch (TrustedRedirectException $e) {
            $this->assertEquals('redirectTrustedURL', $e->getMessage());
            $this->assertEquals('http://other.url.com', $e->getUrl());
            $this->assertEquals([], $e->getParams());
        }
    }

    public function testUntrustedRedirect()
    {


        $params = [
            'state' => '1234'
        ];
        try {
            $this->mockHttp->redirectUntrustedURL('http://my.url.com', $params);
            $this->fail('Exception expected');
        } catch (UntrustedRedirectException $e) {
            $this->assertEquals('redirectUntrustedURL', $e->getMessage());
            $this->assertEquals('http://my.url.com', $e->getUrl());
            $this->assertEquals($params, $e->getParams());
        }
    }
}
