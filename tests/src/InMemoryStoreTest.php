<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2/28/18
 * Time: 4:51 PM
 */

namespace CirrusIdentity\SSP\Test;


class InMemoryStoreTest extends \PHPUnit_Framework_TestCase
{

    protected function tearDown()
    {
        InMemoryStore::clearInternalState();
        \SimpleSAML_Configuration::clearInternalState();
    }

    public function testState()
    {

        // given: an SSP config that overrides the store type
        $config = [
            'store.type' => 'CirrusIdentity\SSP\Test\InMemoryStore',
        ];
        \SimpleSAML_Configuration::loadFromArray($config, '[ARRAY]', 'simplesaml');

        // when: getting the store
        $store = \SimpleSAML\Store::getInstance();

        // then: will give us the right type of store
        $this->assertInstanceOf(InMemoryStore::class, $store);

        // and: we can store stuff
        $this->assertNull($store->get('string', 'key'), 'Key does not exist yet');
        $store->set('string', 'key', 'value');
        $this->assertEquals('value', $store->get('string', 'key'));
        $store->delete('string', 'key');
        $this->assertNull($store->get('string', 'key'), 'Key was removed');


    }

}