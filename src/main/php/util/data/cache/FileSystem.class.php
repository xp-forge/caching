<?php namespace util\data\cache;

use io\{Folder, File, Files, Path};

/** @test util.data.unittest.FileSystemTest */
class FileSystem implements Store {
  private $base;

  /**
   * Creates a new filesystem based cache storage. The given base dir
   * will be created if it does not exist.
   * 
   * @param  io.File|io.Path|string $base
   * @throws io.IOException
   */
  public function __construct($base) {
    if ($base instanceof Folder) {
      $this->base= $base;
    } else if ($base instanceof Path) {
      $this->base= $base->asFolder();
    } else {
      $this->base= new Folder($base);
    }

    $this->base->exists() || $this->base->create();
  }

  /** Isset operator */
  public function offsetExists($key) {
    $f= new File($this->base, md5($key));
    return $f->exists();
  }

  /** Offset operator, read */
  public function offsetGet($key) {
    $f= new File($this->base, md5($key));
    return $f->exists() ? unserialize(Files::read($f)) : null;
  }

  /** Offset operator, write */
  public function offsetSet($key, $value) {
    Files::write(new File($this->base, md5($key)), serialize($value));
  }

  /** Unset operator */
  public function offsetUnset($key) {
    $f= new File($this->base, md5($key));
    $f->exists() && $f->unlink();
  }
}