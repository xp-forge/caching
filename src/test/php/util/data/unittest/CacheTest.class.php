<?php namespace util\data\unittest;

use lang\IllegalStateException;
use unittest\{Assert, Test, Values};

abstract class CacheTest {

  /** @return util.data.cache.Cache */
  protected abstract function cache();

  #[Test]
  public function can_create_fixture() {
    $this->cache();
  }

  #[Test]
  public function store_retuns_item_passed() {
    $cache= $this->cache();
    $return= $cache->store('self', $this);

    Assert::equals($this, $return);
  }

  #[Test]
  public function does_not_contain_non_existant_item() {
    $cache= $this->cache();

    Assert::false($cache->contains('self'));
  }

  #[Test]
  public function contains_item() {
    $cache= $this->cache();
    $cache->store('self', $this);

    Assert::true($cache->contains('self'));
  }

  #[Test]
  public function get_non_existant_item() {
    $cache= $this->cache();

    Assert::null($cache->retrieve('self'));
  }

  #[Test]
  public function get_or_default() {
    $cache= $this->cache();

    Assert::equals($this, $cache->retrieve('self', $this));
  }

  #[Test]
  public function store_item_then_retrieve_it() {
    $cache= $this->cache();
    $cache->store('self', $this);

    Assert::equals($this, $cache->retrieve('self'));
  }

  #[Test]
  public function non_existant_item() {
    $cache= $this->cache();

    Assert::equals($this, $cache->item('self', function() { return $this; }));
  }

  #[Test]
  public function non_existant_item_function_receives_key() {
    $cache= $this->cache();

    Assert::equals(['self' => $this], $cache->item('self', function($key) { return [$key => $this]; }));
  }

  #[Test]
  public function item() {
    $cache= $this->cache();
    $cache->store('self', $this);

    Assert::equals($this, $cache->item('self', function() {
      throw new IllegalStateException('Should not have been invoked');
    }));
  }

  #[Test]
  public function remove_non_existant() {
    $cache= $this->cache();

    Assert::null($cache->remove('self'));
  }

  #[Test]
  public function remove() {
    $cache= $this->cache();
    $cache->store('self', $this);

    Assert::equals($this, $cache->remove('self'));
  }
}