@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(isset($message))
                <h3>{{$message}}</h3>
            @endif
                
            <div class="card">
                <div class="card-header">Add event</div>
                <div class="card-body">
                {!! Form::open(array('url'=>'/admin/addEvent', 'method' => 'post')) !!}
                <div class="form-group">
                    {!!Form::label('title', 'Title:')!!}
                    {!!Form::text('title')!!}
                {!!Form::submit('Add event!', ['class' => 'btn btn-primary form-control'])!!}
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection