<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Poll;

class VoteController extends Controller
{
    public function show(Poll $poll)
    {
        try {
            $poll->load(['options' => function ($query) {
                $query->withCount('votes as total_votes');
            }]);

            $totalVotes = $poll->options->sum('total_votes');
            $options = $poll->options->map(function ($option) use ($totalVotes) {
                return [
                    'id' => $option->id,
                    'title' => $option->title,
                    'votes' => $option->total_votes,
                    'percentage' => $totalVotes > 0 ? number_format(($option->total_votes / $totalVotes) * 100, 1) : 0
                ];
            });

            $data = [
                'poll' => $poll->only("id", "question", "created_at"),
                'options' => $options,
                'total_votes' => $totalVotes
            ];

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function vote()
    {

    }
}
