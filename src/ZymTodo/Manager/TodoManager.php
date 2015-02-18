<?php
namespace ZymTodo\Manager;

use Symfony\Component\Filesystem\Filesystem;

class TodoManager
{
    const ITEMS     = 'items.txt';
    const ARCHIVED  = 'archived.txt';
    
    protected $fs;
    protected $extra;

    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
        $this->initStore();
    }
    
    public function setExtra($extra)
    {
        $this->extra = $extra;
        return $this;
    }
    
    protected function initStore()
    {
        if (! $this->fs->exists('storage')) {
            $this->fs->mkdir('storage');
        }
        
        if (! $this->fs->exists('storage/'. self::ITEMS)) {
            $this->fs->touch('storage/'. self::ITEMS);
        }
        
        if (! $this->fs->exists('storage/'. self::ARCHIVED)) {
            $this->fs->touch('storage/'. self::ARCHIVED);
        }
    }
    
    public function getItems($fileName = self::ITEMS)
    {
        $tag    = null;
        $date   = null;
        $items  = new TodoCollection();

        if ('archived' == $this->extra) {
            $fileName = self::ARCHIVED;
        } elseif (strstr($this->extra, '#')) {
            $tag = $this->extra;
        } elseif (strstr($this->extra, '@')) {
            $date = $this->extra;
        }
        
        $_items = \file_get_contents('storage/'. $fileName);
        if ($_items) {
            $items = new TodoCollection(\explode("\n", $_items));
            if ($date) {
                $items->filterByDate($date);
            }

            if ($tag) {
                $items->filterByTag($tag);
            }
        }
        
        return $items;
    }
    
    public function removeByNumber($number)
    {
        // get all items
        $items = $this->getItems();
        // get item to remove
        $item = $items->getByNumber($number);
        // call remove on collection
        if ($item && $items->removeByNumber($number)) {
            // save collection without removed item
            $this->save($items);
            $this->addToArchive($item);
            return true;
        }
        
        return false;
    }
    
    protected function addToArchive(TodoItem $item)
    {
        $f = fopen('storage/'. self::ARCHIVED, 'a');
        fputs($f, $item->render() ."\n");
        fclose($f);
    }
    
    protected function save(TodoCollection $items)
    {
        $f = fopen('storage/'. self::ITEMS, 'w');
        foreach ($items as $item) {
            if (! empty($item->render())) {
                fputs($f, $item->render() ."\n");
            }
        }
        fclose($f);
    }
}