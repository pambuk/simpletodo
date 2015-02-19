<?php
namespace ZymTodo\Manager;

class TodoItem
{
    protected $data;
    protected $dateStrings = [];
    protected $dateReplacements = [];
    
    public function __construct($data)
    {
        $this->data = $data;
        $this->replaceDate();
    }
    
    public function render()
    {
        return $this->data;
    }
    
    public function buildDateReplacements()
    {
        if ($this->data && strstr($this->data, '@')) {
            $match = $this->matchDates();
            if (isset($match[1])) {
                foreach ($match[1] as $matched) {
                    $this->dateStrings[] = $matched;
                    
                    try {
                        $date = (new \DateTime($matched))->format("Y-m-d");
                        $this->dateReplacements[] = $date;
                    } catch (\Exception $e) {
                        // fail silently, this happens when todo has i.e. an email address
                        $this->dateReplacements[] = $matched;
                    }
                }
            }
        }

        return (count($this->dateReplacements) > 0) ? true : false;
    }
    
    public function replaceDate()
    {
        if ($this->buildDateReplacements()) {
            $this->data = str_replace($this->dateStrings, $this->dateReplacements, $this->data);
        }
    }
    
    public function hasTag($tag)
    {
        $match = [];
        preg_match_all('/(#[a-zA-Z0-9]*)/', $this->data, $match);
        return in_array($tag, $match[1]);
    }
    
    public function hasDate($date)
    {
        if (\strpos($this->data, $date)) {
            return true;
        }
        
        return false;
    }
    
    protected function matchDates($string = null)
    {
        $match = [];

        if ($string) {
            $data = $string;
        } else {
            $data = $this->data;
        }
        
        preg_match_all('/@([a-z0-9\- ]*)/', $data, $match);
        return $match;
    }
    
    public function getNumber()
    {
        $match = [];
        preg_match('/^([0-9]+)\./', $this->data, $match);
        if (isset($match[1])) {
            return (int) $match[1];
        }
        
        return false;
    }
    
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}