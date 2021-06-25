<?php namespace util\data\cache;

/**
 * Cache which expires items after a given duration.
 *
 * @test  util.data.unittest.ExpireAfterTest
 */
class ExpireAfter extends Cache {
  private $duration;
  private $time= 'time';

  /**
   * Create a new instance
   * 
   * @param  array|util.data.cache.Store $store
   * @param  int $duration
   */
  public function __construct($store, $duration) {
    parent::__construct($store);
    $this->duration= $duration;
  }

  /**
   * Use a callable for determining time other than the default `time()`.
   * Returns the given callable. Typically used for testing.
   *
   * @param  callable $time
   * @return callable
   */
  public function use($time) {
    return $this->time= $time;
  }

  /** Returns whether this cache contains an item by a given key. */
  public function contains(string $key): bool {
    return ($item= $this->store[$key] ?? null) && $item[1] > ($this->time)();
  }

  /**
   * Adds or overwrites an existing item in this cache by a given cache
   * key. Returns the item.
   *
   * @param  string $key
   * @param  var $item
   * @return var
   */
  public function store(string $key, $item) {
    $this->store[$key]= [$item, ($this->time)() + $this->duration];
    return $item;
  }

  /**
   * Retrieves an item by a given key. Returns the given default value if this
   * cache does not contain an item for the given key.
   *
   * @param  string $key
   * @param  var $default
   * @return var
   */
  public function retrieve(string $key, $default= null) {
    if ($item= $this->store[$key] ?? null) {
      if ($item[1] > ($this->time)()) return $item[0];
      unset($this->store[$key]); // Clean up expired item
    }
    return $default;
  }

  /**
   * Removes the item associated with the given key. Returns the removed
   * item or NULL if nothing was removed
   *
   * @param  string $key
   * @return var
   */
  public function remove(string $key) {
    if ($item= $this->store[$key] ?? null) {
      try {
        if ($item[1] > ($this->time)()) return $item[0];
      } finally {
        unset($this->store[$key]); // Also removes expired items
      }
    }
    return null;
  }

  /**
   * Fetches an item either from this cache or - if it's not present - by
   * invoking a given function and storing its result in this cache.
   *
   * @param  string $key
   * @param  function(?string): var $fetch
   * @return var
   */
  public function item(string $key, callable $fetch) {
    if ($item= $this->store[$key] ?? null) {
      if ($item[1] > ($this->time)()) return $item[0];
      // Fall through and overwrite expired items
    }

    $item= $fetch($key);
    $this->store[$key]= [$item, ($this->time)() + $this->duration];
    return $item;
  }
}