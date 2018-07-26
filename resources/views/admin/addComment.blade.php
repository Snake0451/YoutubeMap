@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(isset($message))
                <h3>{{$message}}</h3>
            @endif
                
            <div class="card">
                <div class="card-header"><h3>Add comment<h3></div>
                <div class="card-body">
                {!! Form::open(array('url'=>'addComment', 'method' => 'post')) !!}
                @if(isset($parent_id))
                    <div class="form-group">
                        {!!Form::label('parent_comment_id', 'Parent comment:')!!}
                        {!!Form::text('parent_comment_id', null, ['disabled' => 'true'])!!}
                    </div>
                @endif
                <div class="form-group">
                    {!!Form::label('comment_text', 'Comment text:')!!}
                    {!!Form::textarea('comment_text')!!}
                </div>
                <input type="hidden" name="video_id" value={{ $video_id }}></input>
                {!!Form::submit('Add comment!', ['class' => 'btn btn-primary form-control'])!!}
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection