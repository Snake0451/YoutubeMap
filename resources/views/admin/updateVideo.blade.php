@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(isset($message))
                <h3>{{$message}}</h3>
            @endif
                
            <div class="card">
                <div class="card-header"><h2>Update video</h2></div>
                <div class="card-body">
                {!! Form::open(array('url'=>'/admin/updateVideo', 'method' => 'post')) !!}
                <div class="form-group">
                    <h4>Youtube video id: {{ $video->youtube_id }}</h4>
                </div>
                <div class="form-group">
                    {!!Form::label('title', 'Title:')!!}
                    {!!Form::text('title', $video->title)!!}
                </div>
                <div class="form-group">
                    {!!Form::label('decsription', 'Decsription:')!!}
                    {!!Form::textarea('decsription', $video->description)!!}
                </div>
                <div class="form-group">
                    {!!Form::label('latitude', 'Latitude:')!!}
                    {!!Form::text('latitude', $video->latitude)!!}
                </div>
                <div class="form-group">
                    {!!Form::label('longitude', 'Longitude:')!!}
                    {!!Form::text('longitude', $video->longitude)!!}
                </div>
                <input type="hidden" name="id" value="{{ $video->id }}"></input>
                {!!Form::submit('Update video', ['class' => 'btn btn-primary form-control'])!!}
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection