<?php

namespace CirrusIdentity\SSP\Test\Auth;

use AspectMock\Test as test;
use SimpleSAML\Utils\ClearableState;

class MockAuthSource implements ClearableState
{
    /**
     * Store authSourceId to authSource mappings for getById
     * @var array
     */
    static $authSourceMap = [];

    /**
     * Return $authSource when SSP tries to load $authSourceId
     * @param \SimpleSAML\Auth\Source $authSource the auth source to return
     * @param string $authSourceId The auth source ID for this $authSource
     */
    public static function getById(&$authSource, $authSourceId)
    {
        self::$authSourceMap[$authSourceId] = &$authSource;
        // php 5.6 can't seem to use the static map in the closure
        $map = &self::$authSourceMap;
        test::double('\SimpleSAML\Auth\Source', [
            'getById' => function & ($authSourceId, $class) use ($map) {
                $toRet = null;
                if (array_key_exists($authSourceId, $map)) {
                    $toRet = &$map[$authSourceId];
                }
                return $toRet;
            }
        ]);
    }

    /**
     * @return \AspectMock\Proxy\ClassProxy
     */
    public static function completeAuth()
    {
        return test::double('\SimpleSAML\Auth\Source', [
            'completeAuth' => null,
        ]);
    }


    /**
     * Clear any cached internal state.
     */
    public static function clearInternalState()
    {
        $authSourceMap = [];
        test::clean('\SimpleSAML\Auth\Source');
    }
}
