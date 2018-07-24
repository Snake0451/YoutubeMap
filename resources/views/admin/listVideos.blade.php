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
                    <td><form method="get" action="{{'/admin/updateVideo'}}">
                        <input type="hidden" name="id" value="{{$video->id}}"></hidden>
                        <button type="submit">Update</button>
                    </form>
                    <td><form method="get" action="{{'/admin/deleteVideo'}}">
                        <input type="hidden" name="id" value="{{$video->id}}"></hidden>
                        <button type="submit">Delete</button>
                    </form>
                    </td>
                </tr>
            @endforeach
            {{ $videos->links() }}
    </table>
@endsection
    