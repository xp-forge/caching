<?php namespace util\data\unittest;

use unittest\{Assert, Test};
use util\data\cache\ExpireAfter;

class ExpireAfterTest extends CacheTest {
  const EXPIRES = 3600;

  /** @return util.data.cache.Cache */
  protected function cache() { return new ExpireAfter([], self::EXPIRES); }

  /** Returns a time function which is fowardable */
  private function clock(): callable {
    return new class() {
      private $time;
      public function __construct() { $this->time= time(); }
      public function __invoke() { return $this->time; }
      public function forward($seconds) { $this->time+= $seconds; }
    };
  }

  #[Test]
  public function does_not_contain_expired_item() {
    $cache= $this->cache();
    $clock= $cache->use($this->clock());

    $cache->store('name', 'original');
    $clock->forward(self::EXPIRES);
    Assert::false($cache->contains('name'));
  }

  #[Test]
  public function expired_item() {
    $cache= $this->cache();
    $clock= $cache->use($this->clock());

    $cache->store('name', 'original');
    $clock->forward(self::EXPIRES);
    Assert::equals('replaced', $cache->item('name', function() { return 'replaced'; }));
  }

  #[Test]
  public function get_expired_item() {
    $cache= $this->cache();
    $clock= $cache->use($this->clock());

    $cache->store('self', $this);
    $clock->forward(self::EXPIRES);
    Assert::null($cache->retrieve('self'));
  }

  #[Test]
  public function remove_expired_item() {
    $cache= $this->cache();
    $clock= $cache->use($this->clock());

    $cache->store('self', $this);
    $clock->forward(self::EXPIRES);
    Assert::null($cache->remove('self'));
  }
}