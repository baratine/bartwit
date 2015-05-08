<?
include("retwis.php");
include("header.php");

//$r = redisLink();
if (!gt("u") || !($userid = barMapManager()->lookup("users")->get(gt("u")))) {
    header("Location: index.php");
    exit(1);
}
echo("<h2 class=\"username\">".utf8entities(gt("u"))."</h2>");
if (isLoggedIn() && $User['id'] != $userid) {
    $isfollowing = barScoreManager()->lookup("following:".$User['id'])->get($userid);
    if (!$isfollowing) {
        echo("<a href=\"follow.php?uid=$userid&f=1\" class=\"button\">Follow this user</a>");
    } else {
        echo("<a href=\"follow.php?uid=$userid&f=0\" class=\"button\">Stop following</a>");
    }
}
?>
<?
$start = gt("start") === false ? 0 : intval(gt("start"));
showUserPostsWithPagination(gt("u"),$userid,$start,10);
include("footer.php")
?>
