<?php
//require 'Predis/Autoloader.php';
//Predis\Autoloader::register();

require_once('baratine-php/baratine-client.php');
require_once('baratine-php/baratine-bache.php');

function getBaratineUrl() {
   static $barUrl = 'http://localhost:8085/s/pod';
   
   return $barUrl;
}

function getBaratineClient()
{
  static $barClient = null;
  
  if ($barClient == null) {
    $barClient = \baratine\BaratineClient::create(getBaratineUrl());
  }
  
  return $barClient;
}

function lookupMap(/* string */ $url)
{
  return getBaratineClient()->_lookup($url)->_as('\baratine\bache\MapService');
}

function lookupList(/* string */ $url)
{
  return getBaratineClient()->_lookup($url)->_as('\baratine\bache\ListService');
}

function lookupTree(/* string */ $url)
{
  return getBaratineClient()->_lookup($url)->_as('\baratine\bache\TreeService');
}

function lookupCounter(/* string */ $url)
{
  return getBaratineClient()->_lookup($url)->_as('\baratine\bache\CounterService');
}

function getrand() {
    $fd = fopen("/dev/urandom","r");
    $data = fread($fd,16);
    fclose($fd);
    return md5($data);
}

function isLoggedIn() {
    global $User, $_COOKIE;

    if (isset($User)) return true;

    if (isset($_COOKIE['auth'])) {
        //$r = redisLink();
        $authcookie = $_COOKIE['auth'];
        if ($userid = lookupMap("/map/auths")->get($authcookie)) {
            if (lookupMap("/map/user:$userid")->get("auth") != $authcookie) return false;
            loadUserInfo($userid);
            return true;
        }
    }
    return false;
}

function loadUserInfo($userid) {
    global $User;

    //$r = redisLink();
    $User['id'] = $userid;
    $User['username'] = lookupMap("/map/user:$userid")->get("username");
    return true;
}

//function redisLink() {
//    static $r = false;
//
//    if ($r) return $r;
//    $r = new Predis\Client();
//    return $r;
//}

# Access to GET/POST/COOKIE parameters the easy way
function g($param) {
    global $_GET, $_POST, $_COOKIE;

    if (isset($_COOKIE[$param])) return $_COOKIE[$param];
    if (isset($_POST[$param])) return $_POST[$param];
    if (isset($_GET[$param])) return $_GET[$param];
    return false;
}

function gt($param) {
    $val = g($param);
    if ($val === false) return false;
    return trim($val);
}

function utf8entities($s) {
    return htmlentities($s,ENT_COMPAT,'UTF-8');
}

function goback($msg) {
    include("header.php");
    echo('<div id ="error">'.utf8entities($msg).'<br>');
    echo('<a href="javascript:history.back()">Please return back and try again</a></div>');
    include("footer.php");
    exit;
}

function strElapsed($t) {
    $d = time()-$t;
    if ($d < 60) return "$d seconds";
    if ($d < 3600) {
        $m = (int)($d/60);
        return "$m minute".($m > 1 ? "s" : "");
    }
    if ($d < 3600*24) {
        $h = (int)($d/3600);
        return "$h hour".($h > 1 ? "s" : "");
    }
    $d = (int)($d/(3600*24));
    return "$d day".($d > 1 ? "s" : "");
}

function showPost($id) {
    //$r = redisLink();
    $post = (array) lookupMap("/map/post:$id")->getAll();
    if (empty($post)) return false;

    $userid = $post['user_id'];
    $username = lookupMap("/map/user:$userid")->get("username");
    $elapsed = strElapsed($post['time']);
    $userlink = "<a class=\"username\" href=\"profile.php?u=".urlencode($username)."\">".utf8entities($username)."</a>";

    echo('<div class="post">'.$userlink.' '.utf8entities($post['body'])."<br>");
    echo('<i>posted '.$elapsed.' ago via web</i></div>');
    return true;
}

function showUserPosts($userid,$start,$count) {
    //$r = redisLink();
    $key = ($userid == -1) ? "timeline" : "posts:$userid";
    $posts = lookupList("/list/$key")->getRange($start,$start+$count);
    $c = 0;
    foreach($posts as $p) {
        if (showPost($p)) $c++;
        if ($c == $count) break;
    }
    return count($posts) == $count+1;
}

function showUserPostsWithPagination($username,$userid,$start,$count) {
    global $_SERVER;
    $thispage = $_SERVER['PHP_SELF'];

    $navlink = "";
    $next = $start+10;
    $prev = $start-10;
    $nextlink = $prevlink = false;
    if ($prev < 0) $prev = 0;

    $u = $username ? "&u=".urlencode($username) : "";
    if (showUserPosts($userid,$start,$count))
        $nextlink = "<a href=\"$thispage?start=$next".$u."\">Older posts &raquo;</a>";
    if ($start > 0) {
        $prevlink = "<a href=\"$thispage?start=$prev".$u."\">&laquo; Newer posts</a>".($nextlink ? " | " : "");
    }
    if ($nextlink || $prevlink)
        echo("<div class=\"rightlink\">$prevlink $nextlink</div>");
}

function showLastUsers() {
    //$r = redisLink();
    $users = lookupTree("/tree/users_by_time")->getRangeDescendingKeys(0,9);
    
    echo("<div>");
    foreach($users as $u) {
        echo("<a class=\"username\" href=\"profile.php?u=".urlencode($u)."\">".utf8entities($u)."</a> ");
    }
    echo("</div><br>");
}

?>
