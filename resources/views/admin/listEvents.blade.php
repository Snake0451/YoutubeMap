@extends('adminlte::page')
@section('content')
    @if(isset($message))
        <h3>{{$message}}</h3>
    @endif
    <table>
            @foreach($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->count }}</td>
                    <td><form method="get" action="{{'/admin/updateEvent'}}">
                        <input type="hidden" name="id" value="{{$event->id}}"></hidden>
                        <button type="submit">Update</button>
                    </form>
                    <td><form method="post" action="{{'/admin/deleteEvent'}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$event->id}}"></hidden>
                        <button type="submit">Delete</button>
                    </form>
                    </td>
                </tr>
            @endforeach
    </table>
@endsection
    