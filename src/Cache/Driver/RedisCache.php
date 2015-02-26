<?php
/**
 * This file is part of the PPI Framework.
 *
 * @copyright  Copyright (c) 2011-2013 Paul Dragoonis <paul@ppi.io>
 * @license    http://opensource.org/licenses/mit-license.php MIT
 * @link       http://www.ppi.io
 */

namespace PPI\CacheModule\Cache\Driver;

use PPI\CacheModule\Cache\CacheItem;
use PPI\CacheModule\Cache\CacheInterface;

/**
 * Redis cache driver.
 *
 * @author     Paul Dragoonis <paul@ppi.io>
 * @package    PPI
 * @subpackage Cache
 */
class RedisCache implements CacheInterface
{
    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Using a transaction - check if the key exists and obtain the value.
     *
     * @param  string    $key The unique key for the cache item
     * @return CacheItem
     */
    public function get($key)
    {
        list($exists, $value) = $this->redis->multi()->exists($key)->get($key)->exec();

        return new CacheItem($key, $value, $exists);
    }

    /**
     * For data stored with a key in a hash, check its hash/key exists then fetch it
     *
     * @param string $cacheHash
     * @param string $key
     * @return \PPI\CacheModule\Cache\CacheItem
     */
    public function getWithHash($cacheHash, $key)
    {
        list($exists, $value) = $this->redis->multi()->hExists($cacheHash, $key)->hGet($cacheHash, $key)->exec();

        return new CacheItem($key, $value, $exists);
    }

    /**
     * Set a value in the cache.
     *
     * @param  string $key   The unique key for the cache item
     * @param  null   $value
     * @param  null   $ttl
     * @return bool
     */
    public function set($key, $value = null, $ttl = null)
    {
        return $this->redis->set($key, $value, $ttl);
    }

    /**
     * Set a value in the cache for a key and within a hash
     *
     * @param string $cacheHash
     * @param string $key
     * @param string $value
     * @param  $ttl
     * @return mixed 1 if value didn't exist and was added successfully,
     *              0 if the value was already present and was replaced,
     *              FALSE if there was an error.
     */
    public function setWithhash($cacheHash, $key, $value = null)
    {
        return $this->redis->hSet($cacheHash, $key, $value);
    }

    /**
     * Get multiple items from the cache
     *
     * @param  array $keys An array of the keys to be obtained. eg: array('key1', 'key2')
     * @return array The array key is the cache key and the value is the cache value
     *                    Example: array('key1' => array(...), 'key2' => array(...))
     */
    public function getMultiple($keys)
    {
        $cacheValues = $this->redis->mGet($keys);

        $ret = array();
        foreach ($cacheValues as $key => $value) {
            $isHit = $value === false;
            $ret[$key] = new CacheItem($key, $value, $isHit);
        }

        return $ret;
    }

    /**
     * Persist multiple items at the one time
     *
     * @param array $data The items to persist in the cache.
     *                           The array key is the cache key, the array value is the cache value
     *                           Example: array('user' => $user, 'setting' => $setting, 'date' => $date)
     *
     * @param null|integer $ttl Optional. The expiration time for these keys
     *
     * @return array The array key is the cache key and the array value is the result of the set operation
     *                           Example: array('user' => true, 'setting' => true', 'data' => true)
     */
    public function setMultiple($data, $ttl = null)
    {

        // No native TTL support for MSET so we use a transaction
        $transaction = $this->redis->multi();
        foreach ($data as $key => $val) {
            $transaction->set($key, $val, $ttl);
        }

        return $transaction->exec();
    }

    /**
     * Remove an item from the cache
     *
     * @param  string  $key The cache item key to be removed.
     * @return integer The number of keys removed
     */
    public function remove($key)
    {
        return $this->redis->delete($key);
    }

    public function removeMultiple($keys)
    {
        return $this->redis->delete($keys);
    }

    /**
     * Remove all keys from the database
     *
     * @return true
     */
    public function clear()
    {
        return $this->redis->flushAll();
    }
}
