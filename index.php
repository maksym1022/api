<?php
require_once("Classes/RestServer.php");
require_once("Controllers/UsersController.php");
require_once("Controllers/ResetController.php");
require_once("Controllers/FollowController.php");
require_once("Controllers/LikesController.php");
require_once("Controllers/PostsController.php");
require_once("Controllers/CommentsController.php");

$mode = 'production'; // 'debug' or 'production'
$server = new RestServer($mode);
//$server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

switch($_GET['method'])
{
	default:
	$data = "It works!";
	break;

	/**
	* Users
	*/
	case "login":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->Login($_POST["username"], $_POST["password"]);
	break;

	case "register":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->Register($_POST["username"], $_POST["password"], $_POST["email"], 
		isset($_POST["userTypeID"]) ? $_POST["userTypeID"] : 1,
		isset($_POST["userFullname"]) ? $_POST["userFullname"] : "",
        isset($_POST["userInfo"]) ? $_POST["userInfo"] : "",
        isset($_POST["userLat"]) ? $_POST["userLat"] : "",
        isset($_POST["userLong"]) ? $_POST["userLong"] : "",
        isset($_POST["userAddress"]) ? $_POST["userAddress"] : "",
        isset($_POST["userPhone"]) ? $_POST["userPhone"] : "",
        isset($_POST["userWeb"]) ? $_POST["userWeb"] : "",
        isset($_POST["userEmail"]) ? $_POST["userEmail"] : "");
	break;

	case "getProfile":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->Profile($_POST["userID"], $_POST["forUserID"]);
	break;

	case "setProfile":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->UpdateProfile($_POST["userID"], $_POST["email"], // $_FILE["userAvatar"] (FILE)
 		isset($_POST["username"]) ? $_POST["username"] : "",
        isset($_POST["userFullname"]) ? $_POST["userFullname"] : "", 
        isset($_POST["userInfo"]) ? $_POST["userInfo"] : "",
        isset($_POST["userTypeID"]) ? $_POST["userTypeID"] : 1, 
        isset($_POST["userLat"]) ? $_POST["userLat"] : "", 
        isset($_POST["userLong"]) ? $_POST["userLong"] : "",
        isset($_POST["userAddress"]) ? $_POST["userAddress"] : "",
        isset($_POST["userPhone"]) ? $_POST["userPhone"] : "", 
        isset($_POST["userWeb"]) ? $_POST["userWeb"] : "",
        isset($_POST["userEmail"]) ? $_POST["userEmail"] : "");
	break;

	case "getNewUsers":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->GetLatestUsers($_POST["userID"], isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	case "findUsers":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->SearchUsers($_POST["userID"], $_POST["searchTerm"], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	case "getLocationsForLatLong":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->GetLocationsForLatLong($_POST["userID"], $_POST["latitude"], $_POST["latitude"], 
		isset($_POST["distance"]) ? $_POST["distance"] : 50, isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	case "getTimeline":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->Timeline($_POST["userID"], $_POST["forUserID"], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 10);
	break;
	
	case "getUserPost":
	$users = new UsersController();
	$users->server = $server;
	$data = $users->UserPost($_POST["userID"], $_POST["forUserID"], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 10);
	break;
		
	/**
	* Reset
	*/
	case "checkToken":
	$reset = new ResetController();
	$reset->server = $server;
	$data = $reset->GetTokenValid($_POST['username'], $_POST['token']);
	break;

	case "changePassword":
	$reset = new ResetController();
	$reset->server = $server;
	$data = $reset->ChangePassword($_POST['username'], $_POST['token'], $_POST['password']);
	break;

	case "forgotPassword":
	$reset = new ResetController();
	$reset->server = $server;
	$data = $reset->ResetPassword($_POST['email']);
	break;


	/**
	* Follow
	*/
	case "setFollow":
	$follow = new FollowController();
	$follow->server = $server;
	$data = $follow->SetFollow($_POST['userID'], $_POST['followingID']);
	break;

	case "removeFollow":
	$follow = new FollowController();
	$follow->server = $server;
	$data = $follow->RemoveFollow($_POST['userID'], $_POST['followingID']);
	break;

	case "getFollowers":
	$follow = new FollowController();
	$follow->server = $server;
	$data = $follow->GetFollowers($_POST['userID'], $_POST['forUserID'], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	case "getFollowing":
	$follow = new FollowController();
	$follow->server = $server;
	$data = $follow->GetFollowing($_POST['userID'], $_POST['forUserID'], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	/**
	* Likes
	*/
	case "setLike":
	$like = new LikesController();
	$like->server = $server;
	$data = $like->SetLike($_POST['userID'], $_POST["postID"]); 
	break;

	case "removeLike":
	$like = new LikesController();
	$like->server = $server;
	$data = $like->RemoveLike($_POST['userID'], $_POST['postID']);
	break;

	case "getLikes":
	$like = new LikesController();
	$like->server = $server;
	$data = $like->GetLikes($_POST['userID'], $_POST['postID'], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	/**
	* Posts
	*/
	case "sendPost":
	$post = new PostsController();
	$post->server = $server;
	$data = $post->SendPost($_POST['userID'], $_POST["postTitle"], isset($_POST["postKeywords"]) ? $_POST["postKeywords"] : ""); // $_FILE["postImage"] (file)
	break;

	case "getRecentPosts":
	$post = new PostsController();
	$post->server = $server;
	$data = $post->GetRecentPosts($_POST['userID'], isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	case "getPopularPosts":
	$post = new PostsController();
	$post->server = $server;
	$data = $post->GetPopularPosts($_POST['userID'], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

	/**
	* Comments
	*/
	case "getComment":
	$comment = new CommentsController();
	$comment->server = $server;
	$data = $comment->GetComment($_POST['commentID']);
	break;

	case "setComment":
	$comment = new CommentsController();
	$comment->server = $server;
	$data = $comment->SetComment($_POST['userID'], $_POST['postID'], $_POST['commentText']);
	break;

	case "removeComment":
	$comment = new CommentsController();
	$comment->server = $server;
	$data = $comment->RemoveComment($_POST['userID'], $_POST['commentID']);
	break;

	case "getComments":
	$comment = new CommentsController();
	$comment->server = $server;
	$data = $comment->GetComments($_POST['postID'], isset($_POST["page"]) ? $_POST["page"] : 1, isset($_POST["take"]) ? $_POST["take"] : 50);
	break;

}


$server->sendData($data);

?>