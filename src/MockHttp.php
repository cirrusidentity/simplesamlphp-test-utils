<?php
namespace CirrusIdentity\SSP\Test;

use AspectMock\Test as test;
use CirrusIdentity\SSP\Test\Capture\RedirectException;

class MockHttp
{

    static public function throwOnRedirectTrustedURL() {
        test::double('SimpleSAML\Utils\HTTP', [
            'redirectTrustedURL' => function () {
                throw new RedirectException('redirectTrustedURL', func_get_args());
            }
        ]);
    }

    static public function throwOnRedirectUntrustedURL() {
        test::double('SimpleSAML\Utils\HTTP', [
            'redirectUntrustedURL' => function () {
                throw new RedirectException('redirectUntrustedURL', func_get_args());
            }
        ]);
    }

}