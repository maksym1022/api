<?php 
require_once("Settings/config.php");
require_once("Classes/Helper.php");
require_once("Models/Response/SingleItemResponse.php");
require_once("Models/Response/MultipleItemsResponse.php");
require_once("Models/Response/BooleanResponse.php");
require_once("Models/User.php");


class Comment
{
    /** 
    * Get comment
    */
    public static function GetComment($commentID)
    {
        $response = new SingleItemResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $commentID = $mysqli->real_escape_string($commentID);


        if($result = $mysqli -> query("SELECT * FROM comments WHERE commentID = {$commentID} ")) 
        {
            $response->status = 200;
            $row = $result->fetch_object();
            $row->user = User::Profile(0, $row->userID)->item;
            $row->timeAgo = Helper::TimeAgo($row->commentDate);

            $response->item = $row;
            $result->close();
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
    * Set comment
    */
    public static function SetComment($userID, $postID, $commentText)
    {
        $response = new SingleItemResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $postID = $mysqli->real_escape_string($postID);
        $commentText = $mysqli->real_escape_string($commentText);

        if($mysqli -> query("INSERT INTO comments(userID, postID, commentText) VALUES ({$userID}, {$postID}, '{$commentText}') ")) 
        {
            /* Comment OK */
            $response->status = 200;
            $response->item = Comment::GetComment($mysqli->insert_id)->item;
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

    /*
    * Remove follow
    */
    public static function RemoveComment($userID, $commentID)
    {
        $response = new BooleanResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $commentID = $mysqli->real_escape_string($commentID);

        if($mysqli -> query("DELETE FROM comments WHERE userID = {$userID} AND commentID = {$commentID} ")) 
        {
            /* Remove follow OK */
            if($mysqli->affected_rows > 0)
            {
                $response->status = 200;
            }
            else
            {
                $response->status = 404;
            }
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
    * Get comments for post
    */
    public static function GetComments($postID, $page = 1, $take = 50)
    {
        $response = new MultipleItemsResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $postID = $mysqli->real_escape_string($postID);
        $take = $mysqli->real_escape_string($take);
        $from = ($mysqli->real_escape_string($page) - 1) * $take;

        $items = array();

        $sql = "SELECT commentID FROM comments WHERE postID = {$postID}
                ORDER BY commentID DESC
                LIMIT {$from}, {$take}";

        if ($result = $mysqli->query($sql))
        {
            if($result->num_rows > 0) {
                $response->status = 200;
                
                while($row = $result->fetch_object())
                { 
                    $items[] = Comment::GetComment($row->commentID)->item;
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