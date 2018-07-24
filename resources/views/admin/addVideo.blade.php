@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(isset($message))
                <h3>{{$message}}</h3>
            @endif
                
            <div class="card">
                <div class="card-header">Add video</div>
                <div class="card-body">
                {!! Form::open(array('url'=>'/admin/addVideo', 'method' => 'post')) !!}
                <div class="form-group">
                    {!!Form::label('id', 'ID:')!!}
                    {!!Form::text('id')!!}
                </div>
                <div class="form-group">
                    {!!Form::label('title', 'Title:')!!}
                    {!!Form::text('title')!!}
                </div>
                <div class="form-group">
                    {!!Form::label('decsription', 'Decsription:')!!}
                    {!!Form::textarea('description')!!}
                </div>
                <div class="form-group">
                    {!!Form::label('latitude', 'Latitude:')!!}
                    {!!Form::text('latitude')!!}
                </div>
                <div class="form-group">
                    {!!Form::label('longitude', 'Longitude:')!!}
                    {!!Form::text('longitude')!!}
                </div>
                {!!Form::submit('Add video!', ['class' => 'btn btn-primary form-control'])!!}
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection