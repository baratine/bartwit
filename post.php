<?
include("retwis.php");

if (!isLoggedIn() || !gt("status")) {
    header("Location:index.php");
    exit;
}

//$r = redisLink();
$postid = lookupCounter("/counter/next_post_id")->increment(1);
$status = str_replace("\n"," ",gt("status"));
//$r->hmset("post:$postid","user_id",$User['id'],"time",time(),"body",$status);
//XXX: multiple set
lookupMap("/map/post:$postid")->put("user_id",$User['id']);
lookupMap("/map/post:$postid")->put("time",time());
lookupMap("/map/post:$postid")->put("body",$status);

$followers = lookupTree("/tree/followers:".$User['id'])->getRangeKeys(0,-1);
$followers[] = $User['id']; /* Add the post to our own posts too */

foreach($followers as $fid) {
    lookupList("/list/posts:$fid")->pushHead($postid);
}
# Push the post on the timeline, and trim the timeline to the
# newest 1000 elements.
lookupList("/list/timeline")->pushHead($postid);
lookupList("/list/timeline")->trim(0,1000);

header("Location: index.php");
?>
