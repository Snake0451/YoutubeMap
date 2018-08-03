@extends('adminlte::page')
@section('content')
    @if(isset($message))
        <h3>{{$message}}</h3>
    @endif
    @if ($errors->any())
        <h2>
        {{ implode('', $errors->all(':message')) }}
        </h2>
    @endif
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Videos</h3>
            <div class="box-tools">{{ $videos->links('vendor.pagination.lte') }}</div>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                    <tr>
                        <th>Youtube ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Emotion</th>
                        <th>Event</th>
                        <th>Thumbnail</th>
                    </tr>
                    @foreach($videos as $video)
                        <tr>
                            <td>{{ $video->youtube_id }}</td>
                            <td>{{ $video->title }}</td>
                            <td>{{ $video->description }}</td>
                            <td>{{ $video->latitude }}</td>
                            <td>{{ $video->longitude }}</td>
                            <td>{{ $video->emotion->name }}</td>
                            @if(isset($video->event))
                                <td>{{ $video->event->title }}</td>
                            @else
                                <td>No event</td>
                            @endif
                            <td>{{ $video->thumbnail_url }}</td>
                            <td><form method="get" action="{{'/admin/video/' . $video->id}}">
                                <button type="submit" class="btn btn-block btn-primary">Edit</button>
                            </form>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>
    
    <div class="container">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Add video</h3></div>
            <div class="box-body">
            <form action={{URL::current()}} method="POST">
                <div class="form-body">
                        <div class="form-group">
                            <label for="id">Youtube video ID</label>
                            <input type="text" id="id" name="id" class="form-control" placeholder="Youtube video ID">
                        </div>
                        {{-- <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Title">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea rows="4" id="description" name="description" class="form-control" placeholder="Description"></textarea>
                        </div> --}}
                        <div class="form-group">
                            <label for="event_id">Event</label>
                            <select id="event_id" name="event_id" class="form-control">
                                @foreach($event_list as $key=>$event)
                                    <option value="{{$key}}">{{$event}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="emotion_id">Emotion</label>
                            <select id="emotion_id" name="emotion_id" class="form-control">
                                @foreach($emotion_list as $key=>$emotion)
                                    <option value="{{$key}}">{{$emotion}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="latitude" class="control-label">Latitude</label>
                                    <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Latitude">
                                </div>
                            </div>
                            <div class="col-lg-6">    
                                <div class="form-group">
                                    <label for="longitude" class="control-label">Longitude</label>    
                                    <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Longitude">
                                </div>
                            </div>
                        </div> --}}
                        <button type="submit" class="btn btn-block btn-primary">Add video</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
    