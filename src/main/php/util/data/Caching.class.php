<?php namespace util\data;

use util\data\cache\{Persistent, ExpireAfter, FileSystem};

/**
 * Caching DSL
 * 
 * @test  util.data.unittest.CachingTest
 */
class Caching {
  private $store;

  /**
   * Creates a cache based on the given store
   * 
   * @param  array|util.data.cache.Store $store
   */
  public function __construct($store) {
    $this->store= $store;
  }

  /**
   * Returns in-memory caching
   * 
   * @return self
   */
  public static function inMemory() {
    return new self([]);
  }

  /**
   * Returns a filesystem based cache
   * 
   * @param  io.Folder|io.Path|string $base
   * @return self
   */
  public static function inFileSystem($base) {
    return new self(new FileSystem($base));
  }

  public function retainAll() { return new Persistent($this->store); }

  public function withTTL($duration) { return new ExpireAfter($this->store, $duration); }

  public function keepOnly($capacity) {
    return new KeepOnly($this->store, $capacity, function() {
      foreach ($store as $key => $_) return $key;
    });
  }
}