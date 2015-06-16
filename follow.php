<?php
include("retwis.php");

//$r = redisLink();
if (!isLoggedIn() || !gt("uid") || gt("f") === false ||
    !($username = lookupMap("/map/user:".gt("uid"))->get("username"))) {
    header("Location:index.php");
    exit;
}

$f = intval(gt("f"));
$uid = intval(gt("uid"));
if ($uid != $User['id']) {
    if ($f) {
        lookupTree("/tree/followers:".$uid)->put($User['id'],time());
        lookupTree("/tree/following:".$User['id'])->put($uid,time());
    } else {
        lookupTree("/tree/followers:".$uid)->remove($User['id']);
        lookupTree("/tree/following:".$User['id'])->remove($uid);
    }
}
header("Location: profile.php?u=".urlencode($username));
?>
