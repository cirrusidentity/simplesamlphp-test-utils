<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 3/1/18
 * Time: 8:41 PM
 */

namespace CirrusIdentity\SSP\Test\Auth;

/**
 * Records the state passed into the authentication call.
 */
class AuthSourceRecorder extends \SimpleSAML_Auth_Source
{

    static $authenticateInvocations = [];

    /**
     * Stores the state as part of the most recent invocation
     * @param array $state
     */
    public function authenticate(&$state)
    {
        self::$authenticateInvocations[$this->getAuthId()] = $state;
    }

    /**
     * Return any recorded calls to authenicate. Key is the the authId
     * @return array the authentications
     */
    public static function getAuthentications() {
        return self::$authenticateInvocations;
    }
}