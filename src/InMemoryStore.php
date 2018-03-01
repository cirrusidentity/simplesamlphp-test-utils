<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2/28/18
 * Time: 4:47 PM
 */

namespace CirrusIdentity\SSP\Test;

use SimpleSAML\Utils\ClearableState;


/**
 * Class InMemoryStore. Used for testing SSP code.
 *
 * Use it by setting `'store.type'` in config.php to this clas
 */
class InMemoryStore extends \SimpleSAML\Store implements ClearableState
{

    private static $store = [];

    /**
     * Retrieve a value from the data store.
     *
     * @param string $type The data type.
     * @param string $key The key.
     *
     * @return mixed|null The value.
     */
    public function get($type, $key)
    {
        if (array_key_exists($key, self::$store)) {
            //TODO: implement expiration check
            // implement data type check?
            return self::$store[$key]['value'];
        }
        return null;
    }

    /**
     * Save a value to the data store.
     *
     * @param string $type The data type.
     * @param string $key The key.
     * @param mixed $value The value.
     * @param int|null $expire The expiration time (unix timestamp), or null if it never expires.
     */
    public function set($type, $key, $value, $expire = null)
    {
        self::$store[$key] = [
            'type' => $type,
            'value' => $value,
            'expire' => $expire
        ];
    }

    /**
     * Delete a value from the data store.
     *
     * @param string $type The data type.
     * @param string $key The key.
     */
    public function delete($type, $key)
    {
        unset(self::$store[$key]);
    }

    /**
     * Clear any cached internal state.
     */
    public static function clearInternalState()
    {
        self::$store = [];
    }
}