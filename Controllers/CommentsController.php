<?php 
require_once("Classes/Helper.php");
require_once("Models/Comment.php");

class CommentsController
{
    /**
    * Get comment
    */
    public function GetComment($commentID)
    {
        if(Helper::APIKeyValid())
        {
            if(isset($_POST['commentID']))
            {
                $response = Comment::GetComment($commentID);
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
    * Set comment
    */
    public function SetComment($userID, $postID, $commentText)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) && 
                isset($_POST['postID']) &&
                isset($_POST['commentText']))
            {
                $response = Comment::SetComment($userID, $postID, $commentText);
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
    *  Remove comment
    */
    public function RemoveComment($userID, $commentID)
    {
        if(Helper::APIKeyValid($userID))
        {
            if(isset($_POST['userID']) &&
                isset($_POST['commentID']))
            {
                $response = Comment::RemoveComment($userID, $commentID);
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
    * Get comments for post
    */
    public function GetComments($postID, $page = 1, $take = 50)
    {
        if(Helper::APIKeyValid())
        {
            if(isset($_POST['postID']))
            {
                $response = Comment::GetComments($postID, $page, $take);
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