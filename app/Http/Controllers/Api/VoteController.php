<?php

namespace App\Http\Controllers\Api;

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
            $isAlreadyVoted = filled($this->voteService->givenVote($pollId, $ipAddress));

            if ($isAlreadyVoted) {
                return response()->json(['message' => 'You have already voted'], 400);
            }

            $poll = Poll::query()->findOrFail($pollId);

            $vote = Vote::create([
                'poll_id' => $poll->id,
                'option_id' => $request->option_id,
                'user_id' => auth()->id() ?? null,
                'ip_address' => $request->ip()
            ]);

            $poll->load(['options' => function ($query) {
                $query->withCount('votes as total_votes');
            }]);

            return response()->json(['message' => 'Vote submitted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
