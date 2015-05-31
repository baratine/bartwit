<?
include("retwis.php");

if (!isLoggedIn()) {
    header("Location: index.php");
    exit;
}

//$r = redisLink();
$newauthsecret = getrand();
$userid = $User['id'];
$oldauthsecret = lookupMap("/map/user:$userid")->get("auth");

lookupMap("/map/user:$userid")->put("auth",$newauthsecret);
lookupMap("/map/auths")->put($newauthsecret, $userid);
lookupMap("/map/auths")->remove($oldauthsecret);

header("Location: index.php");
?>
