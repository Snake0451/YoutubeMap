<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Video;
use \Illuminate\Database\QueryException;
use App\Comment;
use App\User;
use Auth;

class CommentController extends Controller
{
    public function videoComments ($id)
    {
        try
        {
            $video = Video::find($id);
            $comments = $video->comments()->get();
            foreach($comments as $comment)
            {
                $comment->author = User::find($comment->user_id)->name;
            }
            return response()->json($comments);
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    // public function hierarchy($comments)
    // {
    //     $sortedComments = $comments->where();
    //     foreach($sortedComments as $comment)
    //     {
    //         $comment->$children = array();
    //     }
    //     foreach($comments as $comment)
    //     {
            
    //         if($comment->parent_comment_id != null)
    //         {
    //             array_push($sortedComments[$comment->parent_comment_id]->children, $comment);
    //         }
    //     }   
    // }

    public function addComment ($input) 
    {
        $args = array(
        'user_id' => Auth::user()->id,
        'comment_text' => $input['comment_text'],
        'video_id' => $input['video_id']
        );
        if(isset($input['parent_comment']))
            $args = array_merge($args, array('parent_comment_id' => $input['parent_comment']));
        Comment::create($args);
        return 1;
    }

    public function updateComment ($id) 
    {
        try
        {
            $comment = Comment::find($id); 
            $comment->comment_text = $_POST['comment_text'];
            $comment->save();
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function addCommentForm ($id)
    {
        if(isset($message))
            return view('admin.addComment')->with('message', $message)->with('video_id', $id);
        else
            return view('admin.addComment')->with('video_id', $id);
    }

    public function listVideoComments ($id)
    {
        $comments = CommentController::videoComments($id)->getData();
        return view('admin.listComments')->with('comments', $comments)->with('video_id', $id);
    }

    public function deleteComment ($id)
    {
        try
        {
            $comment = Comment::find($id);
            $comment->delete();
            return 1;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    public function handleDelete () 
    {
        CommentController::deleteComment($_POST['id']);
        return redirect()->back();
    }

    public function handleAdd ()
    {
        CommentController::addComment($_POST);
        return redirect()->back()->with('message', 'Comment has been added');
    }
}
