<?php

use AspectMock\Test as test;
use CirrusIdentity\SSP\Test\Auth\AuthSourceRecorder;
use CirrusIdentity\SSP\Test\Auth\MockAuthSource;

/**
 *
 * Tests mock of various SSP HTTP util static calls
 */
class MockAuthSourceTest extends \PHPUnit_Framework_TestCase
{

    protected function tearDown()
    {
        test::clean(); // remove all registered test doubles
    }

    public function testMockAuthSource() {
        $config = [];
        $source =  new AuthSourceRecorder(['AuthId' => 'authName'], $config);
        $source1 =  new AuthSourceRecorder(['AuthId' => 'otherName'], $config);
        MockAuthSource::getById($source, 'authName');
        $this->assertNull(SimpleSAML_Auth_Source::getById('abc'));
        $this->assertEquals($source, SimpleSAML_Auth_Source::getById('authName'));

        // Adding additional auth sources preservers the previous ones
        MockAuthSource::getById($source1, 'otherName');
        $this->assertEquals($source, SimpleSAML_Auth_Source::getById('authName'));
        $this->assertEquals($source1, SimpleSAML_Auth_Source::getById('otherName'));

    }

    public function testMockCompleteAuth() {
        $double = MockAuthSource::completeAuth();
        $double->verifyNeverInvoked('completeAuth');
        $state = ['myKey' => 'myValue'];
        SimpleSAML_Auth_Source::completeAuth($state);

        // Confirm method called
        $double->verifyInvokedOnce('completeAuth');
        // Confirm method called with specific args
        $double->verifyInvokedOnce('completeAuth', [$state]);

        // Inspect arguments
        $invocations = $double->getCallsForMethod('completeAuth');
        $firstInvocation = $invocations[0];
        $firstArg = $firstInvocation[0];
        $this->assertEquals('myValue', $firstArg['myKey']);
    }

}