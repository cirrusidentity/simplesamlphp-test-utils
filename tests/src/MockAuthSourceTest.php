<?php

use AspectMock\Test as test;
use CirrusIdentity\SSP\Test\Auth\AuthSourceRecorder;
use CirrusIdentity\SSP\Test\Auth\MockAuthSource;
use PHPUnit\Framework\TestCase;
use SimpleSAML\Auth\Source;

/**
 *
 * Tests mock of various SSP HTTP util static calls
 */
class MockAuthSourceTest extends TestCase
{

    protected function tearDown(): void
    {
        MockAuthSource::clearInternalState();
        AuthSourceRecorder::clearInternalState();
    }

    public function testMockAuthSource()
    {
        $config = [];
        $source =  new AuthSourceRecorder(['AuthId' => 'authName'], $config);
        $source1 =  new AuthSourceRecorder(['AuthId' => 'otherName'], $config);
        MockAuthSource::getById($source, 'authName');
        $this->assertNull(SimpleSAML\Auth\Source::getById('abc'));
        $this->assertEquals($source, SimpleSAML\Auth\Source::getById('authName'));

        // Adding additional auth sources preservers the previous ones
        MockAuthSource::getById($source1, 'otherName');
        $this->assertEquals($source, SimpleSAML\Auth\Source::getById('authName'));
        $this->assertEquals($source1, SimpleSAML\Auth\Source::getById('otherName'));
    }

    /**
     * Confirm the mock auth source is storing by reference. This allows access to any source
     * that gets called.
     */
    public function testAuthSourceStoreByReference()
    {
        $source = $this->createMock(Source::class);
        MockAuthSource::getById($source, 'add');

        $loadedSource = SimpleSAML\Auth\Source::getById('add');
        $this->assertEquals($source, $loadedSource);
    }

    public function testMockCompleteAuth()
    {
        $double = MockAuthSource::completeAuth();
        $double->verifyNeverInvoked('completeAuth');
        $state = ['myKey' => 'myValue'];
        SimpleSAML\Auth\Source::completeAuth($state);

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
