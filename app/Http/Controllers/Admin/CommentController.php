<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Video;
use \Illuminate\Database\QueryException;
use App\Comment;
use App\User;
use Auth;

class CommentController extends Controller
{
    public function listComments ($video_id)
    {
        try
        {
            $video = Video::find($video_id);
            $comments = $video->comments()->get();
            if(!$comments)
                return abort(404);
            foreach($comments as $comment)
            {
                $comment->author = User::find($comment->user_id)->name;
            }
            view('admin.listComments')->with('comments', $comments)->with('video_id', $video_id);
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function addComment ($video_id, Request $request) 
    {

        $input = $request->all();
        try
        {
            $args = array(
                'user_id' => Auth::user()->id,
                'comment_text' => $input['comment_text'],
                'video_id' => $video_id
                );
                if(isset($input['parent_comment']))
                    $args = array_merge($args, array('parent_comment_id' => $input['parent_comment']));
                Comment::create($args);
                return redirect()->back()->with('message', 'Comment has been added');
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }
        
    }

    public function updateComment ($video_id, $id) 
    {
        try
        {
            $comment = Comment::find($id); 
            $comment->comment_text = $_POST['comment_text'];
            $comment->save();
            return redirect('/admin/video/'.$video_id)->with('message', 'Comment has been updated');
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function listVideoComments ($video_id)
    {
        $comments = CommentController::videoComments($video_id)->getData();
        if($comments->count())
            return view('admin.listComments')->with('comments', $comments)->with('video_id', $video_id);
        return abort(404);
    }

    public function deleteComment ($video_id, $id)
    {
        try
        {
            $comment = Comment::find($id);
            $comment->delete();
            return redirect('admin/video/' . $video_id)->with('message', 'Comment has been deleted');
        }
        catch(Exception $e)
        {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function showComment ($video_id, $id)
    {
        $comment = Comment::find($id);
        if($comment)
            return view('admin.showComment')->with('comment', $comment);
        return abort(404);
    }
}
