<?
include("retwis.php");

# Form sanity checks
if (!gt("username") || !gt("password") || !gt("password2"))
    goback("Every field of the registration form is needed!");
if (gt("password") != gt("password2"))
    goback("The two password fileds don't match!");

# The form is ok, check if the username is available
$username = gt("username");
$password = gt("password");
//$r = redisLink();
if (barMapManager()->lookup("users")->get($username))
    goback("Sorry the selected username is already in use.");

# Everything is ok, Register the user!
$userid = barCounterManager()->lookup("next_user_id")->increment(1);
$authsecret = getrand();
barMapManager()->lookup("users")->put($username,$userid);
barMapManager()->lookup("user:$userid")->put("username",$username);
barMapManager()->lookup("user:$userid")->put("password",$password);
barMapManager()->lookup("user:$userid")->put("auth",$authsecret);
// XXX: add REST json support
//barMapManager()->lookup("user:$userid")->putMap(array(
//    "username"=>$username,
//    "password"=>$password,
//    "auth"=>$authsecret));
barMapManager()->lookup("auths")->put($authsecret,$userid);

barScoreManager()->lookup("users_by_time")->put($username, time());

# User registered! Login her / him.
setcookie("auth",$authsecret,time()+3600*24*365);

include("header.php");
?>
<h2>Welcome aboard!</h2>
Hey <?=utf8entities($username)?>, now you have an account, <a href="index.php">a good start is to write your first message!</a>.
<?
include("footer.php")
?>
