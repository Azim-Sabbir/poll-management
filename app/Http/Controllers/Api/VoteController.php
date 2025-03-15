<?php

namespace App\Http\Controllers\Api;

use App\Events\VoteUpdated;
use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\Vote;
use App\services\VoteService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function __construct(private VoteService $voteService)
    {}

    public function show($slug)
    {
        try {
            $data = $this->voteService->fetchVote($slug);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function vote(Request $request, $pollId)
    {
        $request->validate([
            'option_id' => 'required|exists:options,id'
        ]);

        $ipAddress = $request->ip();

        try {
            $this->voteService->handleVote(
                $request,
                $pollId,
                $ipAddress
            );

            return response()->json(['message' => 'Vote submitted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
