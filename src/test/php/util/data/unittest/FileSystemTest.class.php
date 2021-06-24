<?php namespace util\data\unittest;

use io\{Folder, Path};
use lang\Environment;
use unittest\{After, Assert, Test};
use util\data\cache\FileSystem;

class FileSystemTest {
  private $cleanup= [];

  /** @return io.Folder */
  private function tempFolder() {
    $this->cleanup[]= $f= new Folder(Environment::tempDir(), uniqid('.cache-'));

    // Take absolutely no chances, uniqid() depends on system time and
    // we might end up with two test runs having the same "unique" ID.
    $f->exists() && $f->unlink();
    return $f;
  }

  #[After]
  public function cleanup() {
    foreach ($this->cleanup as $dir) {
      $dir->exists() && $dir->unlink();
    }
  }

  #[Test, Values(eval: '[["."], [new Path(".")], [new Folder(".")]]')]
  public function can_create($from) {
    new FileSystem($from);
  }

  #[Test]
  public function isset_non_existant() {
    $fixture= new FileSystem($this->tempFolder());

    Assert::false(isset($fixture['test']));
  }

  #[Test]
  public function write_isset_roundtrip() {
    $fixture= new FileSystem($this->tempFolder());
    $fixture['test']= 'value';

    Assert::true(isset($fixture['test']));
  }

  #[Test]
  public function read_non_existant() {
    $fixture= new FileSystem($this->tempFolder());

    Assert::null($fixture['test']);
  }

  #[Test]
  public function write_read_roundtrip() {
    $fixture= new FileSystem($this->tempFolder());
    $fixture['test']= 'value';

    Assert::equals('value', $fixture['test']);
  }

  #[Test]
  public function write_unset_isset_roundtrip() {
    $fixture= new FileSystem($this->tempFolder());
    $fixture['test']= 'value';
    unset($fixture['test']);

    Assert::false(isset($fixture['test']));
  }

  #[Test]
  public function unset_non_existant() {
    $fixture= new FileSystem($this->tempFolder());
    unset($fixture['test']);

    Assert::false(isset($fixture['test']));
  }
}