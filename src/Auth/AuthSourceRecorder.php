<?php

namespace CirrusIdentity\SSP\Test\Auth;

use SimpleSAML\Auth\Source;
use SimpleSAML\Utils\ClearableState;

/**
 * Records the state passed into the authentication call.
 */
class AuthSourceRecorder extends Source implements ClearableState
{

    private static $authenticateInvocations = [];

    /**
     * Stores the state as part of the most recent invocation
     * @param array $state
     */
    public function authenticate(&$state): void
    {
        self::$authenticateInvocations[$this->getAuthId()] = $state;
    }

    /**
     * Return any recorded calls to authenicate. Key is the the authId
     * @return array the authentications
     */
    public static function getAuthentications()
    {
        return self::$authenticateInvocations;
    }

    /**
     * Clear any cached internal state.
     */
    public static function clearInternalState(): void
    {
        self::$authenticateInvocations = [];
    }
}
