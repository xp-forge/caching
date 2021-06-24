<?php namespace util\data\cache;

/** Base class for cache implementations */
abstract class Cache {
  public $store;

  /** @param array|util.data.cache.Store $store */
  public function __construct($store) {
    $this->store= $store;
  }

  /** Returns whether this cache contains an item by a given key. */
  public abstract function contains(string $key): bool;

  /**
   * Adds or overwrites an existing item in this cache by a given cache
   * key. Returns the item.
   *
   * @param  string $key
   * @param  var $item
   * @return var
   */
  public abstract function store(string $key, $item);

  /**
   * Retrieves an item by a given key. Returns the given default value if this
   * cache does not contain an item for the given key.
   *
   * @param  string $key
   * @param  var $default
   * @return var
   */
  public abstract function retrieve(string $key, $default= null);

  /**
   * Fetches an item either from this cache or - if it's not present - by
   * invoking a given function and storing its result in this cache.
   *
   * @param  string $key
   * @param  function(): var $fetch
   * @return var
   */
  public abstract function item(string $key, callable $fetch);

  /**
   * Removes the item associated with the given key. Returns the removed
   * item or NULL if nothing was removed
   *
   * @param  string $key
   * @return var
   */
  public abstract function remove(string $key);
}