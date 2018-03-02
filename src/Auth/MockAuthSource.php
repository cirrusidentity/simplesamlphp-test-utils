<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 3/1/18
 * Time: 5:28 PM
 */

namespace CirrusIdentity\SSP\Test\Auth;
use AspectMock\Test as test;


class MockAuthSource
{
    /**
     * Store authSourceId to authSource mappings for getById
     * @var array
     */
    static $authSourceMap = [];




    /**
     * Return $authSource when SSP tries to load $authSourceId
     * @param \SimpleSAML_Auth_Source $authSource the auth source to return
     * @param string $authSourceId The auth source ID for this $authSource
     */
    static public function getById($authSource, $authSourceId) {
        self::$authSourceMap[$authSourceId] = $authSource;
        // php 5.6 can't seem to use the static map in the closure
        $map = self::$authSourceMap;
        test::double('\SimpleSAML_Auth_Source', [
            'getById' => function ($authSourceId, $class) use ($map) {
               return array_key_exists($authSourceId, $map) ? $map[$authSourceId] : null;
            }
        ]);
    }

    /**
     * @return \AspectMock\Proxy\ClassProxy
     */
    static public function completeAuth() {
        return test::double('\SimpleSAML_Auth_Source', [
            'completeAuth' => null,
        ]);
    }


}