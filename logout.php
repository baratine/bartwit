<?
include("retwis.php");

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

//$r = redisLink();
$newauthsecret = getrand();
$userid = $User['id'];
$oldauthsecret = barMapManager()->lookup("user:$userid")->get("auth");

barMapManager()->lookup("user:$userid")->put("auth",$newauthsecret);
barMapManager()->lookup("auths")->put($newauthsecret, $userid);
barMapManager()->lookup("auths")->remove($oldauthsecret);

header("Location: index.php");
?>
