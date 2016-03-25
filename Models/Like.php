<?php 
require_once("Settings/config.php");
require_once("Classes/Helper.php");
require_once("Models/Response/SingleItemResponse.php");
require_once("Models/Response/MultipleItemsResponse.php");
require_once("Models/Response/BooleanResponse.php");
require_once("Models/User.php");
require_once("Models/Post.php");

class Like
{
    /**
    * Set like
    */
    public static function SetLike($userID, $postID)
    {
        $response = new SingleItemResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $postID = $mysqli->real_escape_string($postID);

        if($mysqli -> query("INSERT INTO likes(userID, postID) VALUES ({$userID}, {$postID}) ")) 
        {
            /* Like OK */
            $response->status = 200;
            $item = new stdClass;
            $item->post = Post::GetPost($userID, $postID)->item;
            $item->user = User::Profile($userID, $userID)->item;
            $item->likeID = $mysqli->insert_id;
            $response->item = $item;
        }
        else 
        {
            if($mysqli->errno == 1169 || $mysqli->errno == 1586 || $mysqli->errno == 1062)
            {
                // Already likes this post
                $response->status = 409;
            }
            else
            {
                $response->status = 503;
            }

            $log = Helper::GetLogger();
            $log->logError($mysqli->error);
        }

        $mysqli->close();
        return $response;
    }

    /**
    * Remove like
    */
    public static function RemoveLike($userID, $postID)
    {
        $response = new BooleanResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $postID = $mysqli->real_escape_string($postID);

        if($mysqli -> query("DELETE FROM likes WHERE postID = {$postID} AND userID = {$userID}")) 
        {
            /* Remove like OK */
            $response->status = 200;
        }
        else 
        {
            $response->status = 503;
            $log = Helper::GetLogger();
            $log->logError($mysqli->error);
        }

        $mysqli->close();
        return $response;
    }

    /**
    * Get likes
    */
    public static function GetLikes($userID, $postID, $page = 1, $take = 50)
    {
        $response = new MultipleItemsResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $postID = $mysqli->real_escape_string($postID);
        $userID = $mysqli->real_escape_string($userID);
        $take = $mysqli->real_escape_string($take);
        $from = $mysqli->real_escape_string(($page - 1) * $take);
        $items = array();

        if($result = $mysqli -> query("SELECT likeID, userID, likeDate FROM likes WHERE postID = {$postID} ORDER BY likeID DESC LIMIT {$from}, {$take}")) 
        {
            if($result->num_rows > 0)
            {
                $response->status = 200;
                while($row = $result->fetch_object())
                {
                    $row->user = User::Profile($userID, $row->userID)->item;
                    $row->timeAgo = Helper::TimeAgo($row->likeDate);
                    $items[] = $row;
                }
            }
            else
            {
                $response->status = 404;
            }

            $result->close();
        }
        else 
        {
            $response->status = 503;
            $log = Helper::GetLogger();
            $log->logError($mysqli->error);
        }

        $mysqli->close();
        $response->items = $items;
        return $response;
    }

}
?>