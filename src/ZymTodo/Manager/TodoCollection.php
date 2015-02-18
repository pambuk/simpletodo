<?php
namespace ZymTodo\Manager;

class TodoCollection extends Collection
{
    public function __construct($data = array()) {
        $newData = [];
        foreach ($data as $item) {
            $newData[] = new TodoItem($item);
        }
        
        parent::__construct($newData);
    }
    
    public function filterByDate($date)
    {
        $matched = [];
        
        if ($this->isLoaded()) {
            foreach ($this->data as $item) {
                if ($item->hasDate($date)) {
                    $matched[] = $item;
                }
            }
            
            $this->data = $matched;
        }
        
        return $matched;
    }

    public function filterByTag($tag)
    {
        $matched = [];
        if ($this->isLoaded()) {
            foreach ($this->data as $item) {
                if ($item->hasTag($tag)) {
                    $matched[] = $item;
                }
            }
            
            $this->data = $matched;
        }
        
        return $matched;
    }
    
    public function getByNumber($number)
    {
        if ($this->isLoaded()) {
            foreach ($this->data as $item) {
                if ($item->getNumber() == $number) {
                    return $item;
                }
            }
        }
        
        return false;
    }
    
    public function removeByNumber($number)
    {
        $removed = false;
        if ($this->isLoaded()) {
            foreach ($this->data as $k => $item) {
                if ($item->getNumber() == $number) {
                    unset($this->data[$k]);
                    $removed = true;
                }
            }
            
            $this->refreshIndices();
            $this->renumber();
        }
        
        return $removed;
    }
    
    public function reverse()
    {
        $this->data = array_reverse($this->data);
    }
    
    protected function renumber()
    {
        if ($this->isLoaded()) {
            foreach ($this->data as $k => $item) {
                $item->setData(preg_replace('/^[0-9]+\./', ($k + 1) . '.', $item->render()));
            }
        }
    }
}