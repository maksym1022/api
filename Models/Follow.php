<?php 
require_once("Settings/config.php");
require_once("Classes/Helper.php");
require_once("Models/Response/SingleItemResponse.php");
require_once("Models/Response/MultipleItemsResponse.php");
require_once("Models/Response/BooleanResponse.php");
require_once("Models/User.php");


class Follow
{
    /**
    * Set follow
    */
    public function SetFollow($userID, $followingID)
    {
        $response = new SingleItemResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $followingID = $mysqli->real_escape_string($followingID);
        $now = date("d-m-Y H:i:s");

        if($result = $mysqli->query("SELECT COALESCE(COUNT(*), 0) as cnt FROM follow where userID = {$userID} and followingID = {$followingID} "))
        {
            if($result->fetch_object()->cnt == 0)
            {
                if($userID == $followingID)
                {
                    // Cannot follow yourself
                    $response->status = 409;
                }
                else
                {
                    if($mysqli -> query("INSERT INTO follow(userID, followingID) VALUES ({$userID}, {$followingID}) ")) 
                    {
                        // Follow OK
                        $response->status = 200;
                        $itm = new stdClass;
                        $itm->followID = $mysqli->insert_id;
                        $itm->follower = User::Profile(0, $userID)->item;
                        $itm->following = User::Profile($userID, $followingID)->item;
                        $itm->timeAgo = Helper::TimeAgo($now);

                        $response->item = $itm;
                    }
                }
            }
            else
            {
                /* Already following */
                $response->status = 409;
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
        return $response;
    }

    /**
    * Remove follow
    */
    public function RemoveFollow($userID, $followingID)
    {
        $response = new BooleanResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $followingID = $mysqli->real_escape_string($followingID);

        if($mysqli -> query("DELETE FROM follow WHERE userID = {$userID} AND followingID = {$followingID} ")) 
        {
            /* Remove follow OK */
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
    * Get followers for user
    */
    public function GetFollowers($userID, $forUserID, $page = 1, $take = 50)
    {
        $response = new MultipleItemsResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $forUserID = $mysqli->real_escape_string($forUserID);
        $take = $mysqli->real_escape_string($take);
        $from = ($mysqli->real_escape_string($page) - 1) * $take;

        $items = array();

        $sql = "SELECT userID, followDate FROM follow WHERE followingID = {$forUserID}
                ORDER BY followID DESC
                LIMIT {$from}, {$take}";

        if ($result = $mysqli->query($sql))
        {
            if($result->num_rows > 0) {
                $response->status = 200;
                
                while($row = $result->fetch_object())
                { 
                    $row->user = User::Profile($userID, $row->userID)->item;
                    $row->timeAgo = Helper::TimeAgo($row->followDate);

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

    /**
    * Get the users the user is following
    */
    public function GetFollowing($userID, $forUserID, $page = 1, $take = 50)
    {
        $response = new MultipleItemsResponse();
        $mysqli = new mysqli(API_DB_SERVER, API_DB_USER, API_DB_PASS, API_DB_NAME);
        $userID = $mysqli->real_escape_string($userID);
        $forUserID = $mysqli->real_escape_string($forUserID);
        $take = $mysqli->real_escape_string($take);
        $from = ($mysqli->real_escape_string($page) - 1) * $take;

        $items = array();

        $sql = "SELECT followingID, followDate FROM follow WHERE userID = {$forUserID}
                ORDER BY followID DESC
                LIMIT {$from}, {$take}";

        if ($result = $mysqli->query($sql))
        {
            if($result->num_rows > 0) {
                $response->status = 200;
                
                while($row = $result->fetch_object())
                { 
                    $row->user = User::Profile($userID, $row->followingID)->item;
                    $row->timeAgo = Helper::TimeAgo($row->followDate);
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