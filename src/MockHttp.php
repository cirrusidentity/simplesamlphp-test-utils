<?php
namespace CirrusIdentity\SSP\Test;

use AspectMock\Test as test;
use CirrusIdentity\SSP\Test\Capture\RedirectException;
use SimpleSAML\Utils\ClearableState;

class MockHttp implements ClearableState
{

    public static function throwOnRedirectTrustedURL($includeArgsInMessage = false)
    {
        test::double('SimpleSAML\Utils\HTTP', [
            'redirectTrustedURL' => function () use ($includeArgsInMessage) {
                $msg = 'redirectTrustedURL';
                if ($includeArgsInMessage) {
                    $msg .= ': ' .  var_export(func_get_args(), true);
                }
                throw new RedirectException($msg, func_get_args());
            }
        ]);
    }

    public static function throwOnRedirectUntrustedURL()
    {
        test::double('SimpleSAML\Utils\HTTP', [
            'redirectUntrustedURL' => function () {
                throw new RedirectException('redirectUntrustedURL', func_get_args());
            }
        ]);
    }

    /**
     * Clear any cached internal state.
     */
    public static function clearInternalState()
    {
        test::clean('SimpleSAML\Utils\HTTP');
    }
}
