@extends('adminlte::page')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                <form action="URL::current()" method="POST")) !!}
                <div class="form-group">
                    {!!Form::label('comment_text', 'Comment text:')!!}
                    {!!Form::textarea('comment_text', $comment->comment_text)!!}
                </div>
                {!!Form::submit('Update comment!', ['class' => 'btn btn-primary form-control'])!!}
                {!! Form::close() !!}
                </div>
                <form method="post" action="{{ URL::current() }}">
                    <input type="hidden" name="_method" value="delete" />
                    @csrf
                    <button type="submit">Delete comment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection