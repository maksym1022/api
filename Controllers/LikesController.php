<?php 
require_once("Classes/Helper.php");
require_once("Models/Like.php");

class LikesController
{
    /**
    * Set like
    */
    public function SetLike($userID, $postID)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) && 
                isset($_POST['postID']))
            {
                $response = Like::SetLike($userID, $postID);
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
    * Remove like
    */
    public function RemoveLike($userID, $postID)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) &&
                isset($_POST['postID']))
            {
                $response = Like::RemoveLike($userID, $postID);
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
    *  Get likes for user
    */
    public function GetLikes($userID, $postID, $page = 1, $take = 50)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) &&
                isset($_POST['postID']))
            {
                $response = Like::GetLikes($userID, $postID, $page, $take);
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