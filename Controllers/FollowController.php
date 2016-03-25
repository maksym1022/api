<?php 
require_once("Classes/Helper.php");
require_once("Models/Follow.php");

class FollowController
{
    /**
    * Set follow
    */
    public function SetFollow($userID, $followingID)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) && 
                isset($_POST['followingID']))
            {
                $response = Follow::SetFollow($userID, $followingID);
            }
            else
            {
                $response->status = 400;
            }
        }
        else {
            $response->status = 400;
        }

        $this->server->setStatus($response->status);
        $response->status = Helper::GetCustomStatus($response->status, $this->server);

        return $response;
    }

    /**
    * Remove follow
    */
    public function RemoveFollow($userID, $followingID)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) && 
                isset($_POST['followingID']))
            {
                $response = Follow::RemoveFollow($userID, $followingID);
            }
            else
            {
                $response->status = 400;
            }
        }
        else {
            $response->status = 400;
        }

        $this->server->setStatus($response->status);
        $response->status = Helper::GetCustomStatus($response->status, $this->server);

        return $response;
    }

    /*
    *  Get followers
    */
    public function GetFollowers($userID, $forUserID, $page = 1, $take = 50)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) && isset($_POST['forUserID']))
            {
                $response = Follow::GetFollowers($userID,  $forUserID, $page, $take);
            }
            else
            {
                $response->status = 400;
            }
        }
        else {
            $response->status = 400;
        }

        $this->server->setStatus($response->status);
        $response->status = Helper::GetCustomStatus($response->status, $this->server);

        return $response;
    }

    /**
    * Get the users the user is following
    */
    public function GetFollowing($userID, $forUserID, $page = 1, $take = 50)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) && isset($_POST['forUserID']))
            {
                $response = Follow::GetFollowing($userID, $forUserID, $page, $take);
            }
            else
            {
                $response->status = 400;
            }
        }
        else {
            $response->status = 400;
        }

        $this->server->setStatus($response->status);
        $response->status = Helper::GetCustomStatus($response->status, $this->server);

        return $response;
    }

}
?>