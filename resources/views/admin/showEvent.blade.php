@extends('adminlte::page')
@section('content')
    @if(isset($message))
        <h3>{{$message}}</h3>
    @endif
    
    <table>
            @foreach($videos as $video)
                <tr>
                    <td><a href="{{'/admin/video/' . $video->id}}">{{ $video->youtube_id }}</a></td>
                    <td>{{ $video->title }}</td>
                    </form>
                </tr>
            @endforeach
    </table>
    {!! Form::open(array('url'=>URL::current(), 'method' => 'put')) !!}
    <div class="form-group">
        {!!Form::label('title', 'Event title:')!!}
        {!!Form::textarea('title', $event->title)!!}
    </div>
    {!!Form::submit('Update event!', ['class' => 'btn btn-primary form-control'])!!}
    {!! Form::close() !!}
    </div>
        <form method="post" action="{{ URL::current() }}">
        <input type="hidden" name="_method" value="delete" />
        @csrf
        <button type="submit">Delete comment</button>
    </form>

@endsection