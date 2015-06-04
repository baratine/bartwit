<?php

namespace baratine\cache;

require_once('jamp-client.php');

abstract class MapService
{
  public abstract function get(/* string */ $key);
  
  public abstract function getAll();
  
  public abstract function put(/* string */ $key, $value);
  
  public abstract function putMap(array $map);
  
  public abstract function containsKey(/* string */ $key);
  
  public abstract function remove(/* string */ $key);
  
  public abstract function size();
  
  public abstract function clear();
}

abstract class ListService
{
  public abstract function get(/* string */ $index);
  
  public abstract function getAll();
  
  public abstract function getRange(/* int */ $start, /* int */ $end);
  
  public abstract function pushHead($value);
  
  public abstract function pushTail($value);
  
  public abstract function popHead();
  
  public abstract function popTail();
  
  public abstract function trim(/* int */ $start, /* int */ $end);
  
  public abstract function size();
  
  public abstract function clear();
}

abstract class TreeService
{
  public abstract function get(/* string */ $key);
  
  public abstract function put(/* string */ $key, /* int/string */ $score);
  
  public abstract function remove(/* string */ $key);
  
  public abstract function getRange(/* int */ $start, /* int */ $end);
  
  public abstract function getRangeDescending(/* int */ $start, /* int */ $end);
  
  public abstract function getRangeKeys(/* int */ $start, /* int */ $end);
  
  public abstract function getRangeDescendingKeys(/* int */ $start, /* int */ $end);
  
  public abstract function size();
  
  public abstract function clear();
}

abstract class CounterService
{
  public abstract function get();
  
  public abstract function set(/* int */ $value);
  
  public abstract function increment(/* int */ $value);
  
  public abstract function delete();
}

function test()
{
  
}

//echo "<pre>";
//test();

//die();

