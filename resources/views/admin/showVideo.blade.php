@extends('adminlte::page') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(isset($message))
            <h3> 
                {{ $message }}
            </h3>
            @endif
            <div class="container">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Update video</h3>
                    </div>
                    <div class="box-body">
                        <img src="{{ $video->thumbnail_url }}">
                        <form action={{ URL::current() }} method="post">
                            <input type="hidden" name="_method" value="put" />
                            @csrf
                            <div class="form-body">
                                <div class="form-group">
                                    <label for="id">Youtube video ID</label>
                                    <input type="text" id="id" name="id" class="form-control" placeholder="{{$video->youtube_id}}">
                                </div>
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="{{$video->title}}">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea rows="4" id="description" name="description" class="form-control"
                                        placeholder="{{$video->description}}"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="event_id">Event</label>
                                    <select id="event_id" name="event_id" class="form-control">@foreach($event_list as $key=>$event)
                                        <option value="{{$key}}"> {{ $event }}

                                        </option>
                                            @endforeach 
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label for="emotion_id">Emotion</label>
                                    <select id="emotion_id" name="emotion_id" class="form-control">@foreach($emotion_list as $key=>$emotion)
                                        <option value="{{$key}}"> {{ $emotion }}

                                        </option>
                                            @endforeach 
                                        </select>
                                </div>@if(isset($video->latitude) && isset($video->longitude))
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="latitude" class="control-label">Latitude</label>
                                            <input type="text" id="latitude" name="Latitude" class="form-control"
                                                placeholder="{{$video->latitude}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="longitude" class="control-label">Longitude</label>
                                            <input type="text" id="longitude" name="Longitude" class="form-control"
                                                placeholder="{{$video->longitude}}">
                                        </div>
                                    </div>
                                </div>@else
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group has-warning">
                                            <label for="latitude" class="control-label">
                                                <i class="fa fa-bell-o"></i>Latitude</label>
                                            <input type="text" id="latitude" name="Latitude" class="form-control"
                                                placeholder="Latitude">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group has-warning">
                                            <label for="longitude" class="control-label">
                                                <i class="fa fa-bell-o"></i>Longitude</label>
                                            <input type="text" id="longitude" name="Longitude" class="form-control"
                                                placeholder="Longitude">
                                        </div>
                                    </div>
                                </div>@endif
                                <button type="submit" class="btn btn-block btn-primary">Update video</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Delete video</h3>
        </div>
            <div class="box-body">    
                <form method="post" action="{{ URL::current() }}">
                    <input type="hidden" name="_method" value="delete" />
                    @csrf
                    <button type="submit" class="btn btn-block btn-danger">Delete video</button>
                </form>
            </div>
        </div>
    
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Update video</h3>
        </div>
        <div class="box-body">
            <div>
                <h2>Comments</h2>
                <table class="table table-bordered">@foreach($comments as $comment)
                    <tr>
                        <td> {{ $comment->author }}

                        </td>
                        <td> {{ $comment->comment_text }}

                        </td>
                        <td>
                            <form method="get" action="{{URL::current() . '/comment/' . $comment->id}}">
                                <button type="submit">Edit</button>
                            </form>
                        </td>
                    </tr>@endforeach </table>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Add comment</h3>
        </div>
        <div class="box-body">
            <form action="{{URL::current() . '/comment'}}" method="POST">
            @csrf
            <div class="form-group"> 
                <label for="comment_text">Comment text</label>
                <textarea class="form-control" rows="4" id="comment_text" name="comment_text"></textarea>
            </div> 
            <button type="submit" class="btn btn-primary form-control">Add comment</button>
        </div>
    </div>
</div>
@endsection