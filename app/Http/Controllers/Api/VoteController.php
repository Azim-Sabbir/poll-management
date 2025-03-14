<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function show($slug)
    {
        try {
            $poll = Poll::query()->where('slug', $slug)->firstOrFail();
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

    public function vote(Request $request, $pollId)
    {
        $request->validate([
            'option_id' => 'required|exists:options,id'
        ]);

        $ipAddress = $request->ip();

        try {
            $isAlreadyVoted = $this->isAlreadyVoted($pollId, $ipAddress);

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

    public function isAlreadyVoted($pollId, $ip): bool
    {
        $userId = auth()->id();
        return Vote::query()
            ->where('poll_id', $pollId)
            ->when($userId, function ($query) use($userId) {
                $query->where('user_id', $userId);
            })
            ->when(blank($userId), function ($query) use ($ip) {
                $query->where('ip_address', $ip);
            })
            ->exists();
    }
}
