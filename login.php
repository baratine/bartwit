<?php
include("retwis.php");

# Form sanity checks
if (!gt("username") || !gt("password"))
    goback("You need to enter both username and password to login.");

# The form is ok, check if the username is available
$username = gt("username");
$password = gt("password");
//$r = redisLink();
$userid = lookupMap("/map/users")->get($username);
if (!$userid)
    goback("Wrong username or password");
$realpassword = lookupMap("/map/user:$userid")->get("password");
if ($realpassword != $password)
    goback("Wrong useranme or password");

# Username / password OK, set the cookie and redirect to index.php
$authsecret = lookupMap("/map/user:$userid")->get("auth");
setcookie("auth",$authsecret,time()+3600*24*365);
header("Location: index.php");
?>
