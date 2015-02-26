<?php
/**
 * This file is part of the PPI Framework.
 *
 * @copyright  Copyright (c) 2011-2013 Paul Dragoonis <paul@ppi.io>
 * @license    http://opensource.org/licenses/mit-license.php MIT
 * @link       http://www.ppi.io
 */

namespace PPI\CacheModule\Cache;

/**
 * CacheAwareInterface.
 *
 * @package    PPI
 * @subpackage Cache
 */
interface CacheAwareInterface
{
    /**
     * Sets a cache instance on the object
     *
     * @param  CacheInterface $cache
     * @return null
     */
    public function setCache(CacheInterface $cache);
}
