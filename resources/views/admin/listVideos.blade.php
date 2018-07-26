@extends('adminlte::page')
@section('content')
    @if(isset($message))
        <h3>{{$message}}</h3>
    @endif
    <table>
            @foreach($videos as $video)
                <tr>
                    <td>{{ $video->youtube_id }}</td>
                    <td>{{ $video->title }}</td>
                    <td>{{ $video->description }}</td>
                    <td>{{ $video->latitude }}</td>
                    <td>{{ $video->longitude }}</td>
                    @if(isset($video->event))
                        <td>{{ $video->event->title }}</td>
                    @else
                        <td>No event</td>
                    @endif
                    <td><form method="get" action="{{'/admin/updateVideo'}}">
                        <input type="hidden" name="id" value="{{$video->id}}"></hidden>
                        <button type="submit">Update</button>
                    </form>
                    <td><form method="post" action="{{'/admin/deleteVideo'}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$video->id}}"></hidden>
                        <button type="submit">Delete</button>
                    </form>
                    </td>
                    <td><form action="{{'/admin/listComments/' . $video->id}}">
                        {{-- @csrf --}}
                        {{-- <input type="hidden" name="id" value="{{$video->id}}"></hidden> --}}
                        <button type="submit">Comments</button>
                    </form>
                    </td>
                </tr>
            @endforeach
            {{ $videos->links() }}
    </table>
@endsection
    