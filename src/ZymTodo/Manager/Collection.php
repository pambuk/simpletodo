<?php
namespace ZymTodo\Manager;

abstract class Collection implements \Iterator, \Countable, \ArrayAccess
{
    private $position   = 0;
    protected $data     = array();
    
    public function __construct($data = array()) {
        $this->data     = $data;
        $this->position = 0;
    }
    
    public function current() {
        return $this->data[$this->position];
    }
    
    public function key() {
        return $this->position;
    }
    
    public function next() {
        $this->position++;
    }
    
    public function rewind() {
        $this->position = 0;
    }
    
    public function valid() {
        return isset($this->data[$this->position]);
    }
    
    public function count() {
        $count = 0;
        if (false !== $this->data) {
            $count = count($this->data);
        }

        return $count;
    }
    
    public function isLoaded() {
        return ($this->count()) ? true : false;
    }
    
    /**
     * @param int $offset array index
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    /**
     * @param int $offset array index
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->data[$offset];
    }

    /**
     * @param int|null $offset
     * @param mixed $value
     * @return \mimi\Collection
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[]           = $value;
        } else {
            $this->data[$offset]    = $value;
        }
        
        return $this;
    }

    /**
     * @param int $offset array offset
     * @return \mimi\Collection
     */
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
        return $this;
    }
    
    public function refreshIndices() {
        if (count($this->data) > 0) {
            $this->data = array_values($this->data);
        }

        return $this;
    }
}