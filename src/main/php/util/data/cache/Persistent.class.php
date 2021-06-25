<?php namespace util\data\cache;

/**
 * Persistent cache
 *
 * @test  util.data.unittest.PersistentTest
 */
class Persistent extends Cache {

  /** Returns whether this cache contains an item by a given key. */
  public function contains(string $key): bool {
    return isset($this->store[$key]);
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
    $this->store[$key]= [$item];
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
    return ($item= $this->store[$key] ?? null) ? $item[0] : $default;
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
        return $item[0];
      } finally {
        unset($this->store[$key]);
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
    if ($item= $this->store[$key] ?? null) return $item[0];

    $item= $fetch($key);
    $this->store[$key]= [$item];
    return $item;
  }
}