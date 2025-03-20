<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        try {
            $polls = Poll::query()
                ->select('id', 'question', 'created_at', 'slug')
                ->with(['options' => function ($query) {
                    $query->withCount('votes as total_votes');
                }])->orderByDesc("id")->get();

            return response()->json(new PollResource($polls), 200);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
