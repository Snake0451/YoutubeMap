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
        <h3 class="box-title">Events</h3>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Number of videos</th>
            </tr>
            @foreach($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->count }}</td>
                <td>
                    <form method="get" action="{{URL::current() . '/' . $event->id}}">
                        <button type="submit" class="btn btn-block btn-primary">Edit</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header">Add event</div>
    <div class="card-body">
        <form action="{{URL::current()}}" method="POST">
            <div class="form-group">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Title">
                </div>
                <button type="submit" class="btn btn-block btn-primary">Add event</button>
            </div>
            @endsection