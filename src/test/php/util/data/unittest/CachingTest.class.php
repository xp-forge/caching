<?php namespace util\data\unittest;

use unittest\{Assert, Test};
use util\data\Caching;
use util\data\cache\{Persistent, ExpireAfter};

class CachingTest {

  #[Test]
  public function memory_factory() {
    Caching::inMemory();
  }

  #[Test]
  public function filesystem_factory() {
    Caching::inFileSystem('.');
  }

  #[Test]
  public function using_array_object() {
    $backing= new \ArrayObject();
    $cache= (new Caching($backing))->retainAll();
    $cache->store('key', 'value');

    Assert::equals(['key' => ['value']], iterator_to_array($backing));
  }

  #[Test]
  public function retainAll() {
    Assert::instance(Persistent::class, Caching::inMemory()->retainAll());
  }

  #[Test]
  public function withTTL() {
    Assert::instance(ExpireAfter::class, Caching::inMemory()->withTTL(3600));
  }

  #[Test, Ignore('Not yet implemented')]
  public function retainMRU() {
    Assert::instance(RetainMRU::class, Caching::inMemory()->retainMRU(100));
  }

  #[Test, Ignore('Not yet implemented')]
  public function retainMax() {
    Assert::instance(RetainMax::class, Caching::inMemory()->retainMax(100));
  }
}