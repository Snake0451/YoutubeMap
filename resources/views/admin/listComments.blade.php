@extends('adminlte::page')
@section('content')
    @if(isset($message))
        <h3>{{$message}}</h3>
    @endif
    <table>
            @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->author }}</td>
                    <td>{{ $comment->comment_text }}</td>
                    <td><form method="get" action="{{'/admin/updateComment'}}">
                        <input type="hidden" name="id" value="{{$comment->id}}"></hidden>
                        <button type="submit">Update</button>
                    </form>
                    <td><form method="post" action="{{'/admin/deleteComment'}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$comment->id}}"></hidden>
                        <button type="submit">Delete</button>
                    </form>
                    </td>
                </tr>
            @endforeach
    </table>
    <form method="get" action="{{'/admin/addComment/' . $video_id}}">
        <button type="submit">Add comment</button>
    </form>
@endsection