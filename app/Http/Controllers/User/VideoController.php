<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Video;

class VideoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     path="/api/video",
     *     description="Returns list of all videos.",
     *     operationId="api.video.index",
     *     produces={"application/json"},
     *     tags={"video"},
     *     @SWG\Response(
     *         response=200,
     *         description="Returns list of videos."
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     )
     * )
     *      * @SWG\Get(
     *     path="/api/video/{video_id}",
     *     description="Returns a video with specified ID.",
     *     operationId="api.video.show",
     *     produces={"application/json"},
     *     tags={"video"},
     *     @SWG\Parameter(
     *          name="video_id",
     *          in="path",
     *          required=true,
     *          type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returns video."
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     )
     * )
     * 
     *      * @SWG\Get(
     *      path="/api/playlist",
     *      description="Returns collection of videos that matches event and/or emotions",
     *      operationId="api.video.playlist",
     *      produces={"application/json"},
     *      tags={"video"},
     *      @SWG\Parameter(
     *          name="event_id",
     *          description="ID of event",
     *          in="query",
     *          required=false,
     *          type="integer"
     *          ),
     *      @SWG\Parameter(
     *          name="emotion_id_array",
     *          description="JSON string with emotion ID's.",
     *          in="query",
     *          required=false,
     *          type="string",
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="Returns playlist."
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     )
     * )
     */
    
     public function index ()
    {
        $videos = Video::all();
        return response()->json($videos);
    }

    public function show ($video_id)
    {
        $video = Video::find($video_id);
        return response()->json($video);
    }
    
    public function playlist (Request $requset)
    {
        $input = $requset->all();

        $videos = Video::all();
        //$id_array = array();
        $event_id = null;

        if(isset($input['emotion_id_array']) && isset($input['event_id']))
        {
            $id_array = json_decode($input['emotion_id_array'], true);
            if($input['event_id'] != -1)
                $event_id = $input['event_id'];
            $videos = $videos->whereIn('emotion_id', $id_array['ids'])->where('event_id', $event_id);
        }
        else if(isset($input['emotion_id_array']))
        {
            $id_array = json_decode($input['emotion_id_array'], true);
            $videos = $videos->whereIn('emotion_id', $id_array['ids']);
        }
        else if(isset($input['event_id']))
        {
            if($input['event_id'] != -1)
                $event_id = $input['event_id'];
            $videos = $videos->where('event_id', $event_id);
        }
        return response()->json($videos);
    }
}
