<?php

$file = file_get_contents(__DIR__ . '/input',false);
$lines = explode("\n", $file);

$map = new Map();

foreach ($lines as $line) {
  $suggestion = Suggestion::fromLine($line);
  $map->placeSuggestion($suggestion);
  unset($suggestion);
}

echo $map->overlaps() . " / " . $map->countCells();

class Suggestion {
  private $id;
  private $item;

  public function __construct($id, Item $item)
  {
    $this->id = $id;
    $this->item = $item;
  }

  public static function fromLine($line)
  {
    list($id, $location, $size) = explode(" ", str_replace(":", "", str_replace("@ ","", $line)));
    return new self($id,
      new Item(
        new Location(...explode(",", $location)),
        new Size(...explode("x",$size))
      )
    );
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return Item
   */
  public function getItem(): Item
  {
    return $this->item;
  }
}

class Item {
  private $location;
  private $size;

  public function __construct(Location $location, Size $size)
  {
    $this->location = $location;
    $this->size = $size;
  }

  /**
   * @return mixed
   */
  public function getLocation(): Location
  {
    return $this->location;
  }

  /**
   * @return mixed
   */
  public function getSize(): Size
  {
    return $this->size;
  }
}

class Location {
  private $x;
  private $y;

  public function __construct($x = 0, $y = 0)
  {
    $this->x = $x;
    $this->y = $y;
  }

  public function __toString()
  {
    return (string)($this->x . "," . $this->y);
  }

  /**
   * @return int
   */
  public function getX(): int
  {
    return $this->x;
  }

  /**
   * @return int
   */
  public function getY(): int
  {
    return $this->y;
  }
}

class Size {
  private $length;
  private $width;

  public function __construct($length = 0, $width = 0)
  {
    $this->length = $length;
    $this->width = $width;
  }

  /**
   * @return mixed
   */
  public function getLength()
  {
    return $this->length;
  }

  /**
   * @return mixed
   */
  public function getWidth()
  {
    return $this->width;
  }
}

class Cell {
  private $location;
  /** @var array */
  private $members = [];

  public function __construct(Location $location, $members = [])
  {
    $this->location = $location;
    $this->members = $members;
  }

  public function addMember($id)
  {
    if (!in_array($id, $this->members)) {
      $this->members[] = $id;
    }
    return $this;
  }

  /**
   * @return Location
   */
  public function getLocation(): Location
  {
    return $this->location;
  }

  /**
   * @return array
   */
  public function getMembers(): array
  {
    return $this->members;
  }
}

class Map {
  /** @var Cell[] */
  private $map = [];

  public function placeSuggestion(Suggestion $suggestion)
  {
    $locationFrom = $suggestion->getItem()->getLocation();
    $x = $suggestion->getItem()->getSize()->getLength();
    $y = $suggestion->getItem()->getSize()->getWidth();
    for ($i = $locationFrom->getX(); $i < $locationFrom->getX() + $x; $i++) {
      for ($j = $locationFrom->getY(); $j < $locationFrom->getY() + $y; $j++) {
        $location = new Location($i, $j);
        $this->map[(string)$location]++;
        // reached memory limit
        //$cell = $this->getCellByLocation($location);
        //$cell->addMember($suggestion->getId());
        unset($location);
      }
    }
  }

  private function getCellByLocation(Location $location)
  {
    $locationId = (string) $location;
    return isset($this->map[$locationId]) ? $this->map[$locationId] : null;
  }

  public function overlaps()
  {
    return count(array_filter($this->map, function($i) {
      return $i > 1;
    }));
  }

  /**
   * @return int
   */
  public function countOverlaps()
  {
    return count(array_filter($this->map, function(Cell $cell) {
      return count($cell->getMembers()) > 0;
    }));
  }

  /**
   * @return int
   */
  public function countCells()
  {
    return count($this->map);
  }
}

