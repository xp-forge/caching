<?php namespace util\data\unittest;

use util\data\cache\Persistent;

class PersistentTest extends CacheTest {

  /** @return util.data.cache.Cache */
  protected function cache() { return new Persistent($backing= []); }
}