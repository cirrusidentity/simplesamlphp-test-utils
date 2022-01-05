<?php

namespace CirrusIdentity\SSP\Test;

use SimpleSAML\Store\StoreInterface;
use SimpleSAML\Utils\ClearableState;

/**
 * Class InMemoryStore. Used for testing SSP code.
 *
 * Use it by setting `'store.type'` in config.php to this clas
 */
class InMemoryStore implements StoreInterface, ClearableState
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
    public function get(string $type, string $key)
    {
        if (array_key_exists($key, self::$store)) {
            $item = self::$store[$key];
            if (isset($item['expire']) && $item['expire'] < time()) {
                $this->delete($type, $key);
                return null;
            }
            return $item['value'];
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
    public function set(string $type, string $key, $value, ?int $expire = null): void
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
    public function delete(string $type, string $key): void
    {
        unset(self::$store[$key]);
    }

    /**
     * Clear any cached internal state.
     */
    public static function clearInternalState(): void
    {
        self::$store = [];
    }
}
