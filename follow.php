<?
include("retwis.php");

//$r = redisLink();
if (!isLoggedIn() || !gt("uid") || gt("f") === false ||
    !($username = barMapManager()->lookup("user:".gt("uid"))->get("username"))) {
    header("Location:index.php");
    exit;
}

$f = intval(gt("f"));
$uid = intval(gt("uid"));
if ($uid != $User['id']) {
    if ($f) {
        barScoreManager()->lookup("followers:".$uid)->put($User['id'],time());
        barScoreManager()->lookup("following:".$User['id'])->put($uid,time());
    } else {
        barScoreManager()->lookup("followers:".$uid)->remove($User['id']);
        barScoreManager()->lookup("following:".$User['id'])->remove($uid);
    }
}
header("Location: profile.php?u=".urlencode($username));
?>
