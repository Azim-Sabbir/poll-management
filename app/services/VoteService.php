<?php

namespace App\services;

use App\Models\Poll;
use App\Models\Vote;

class VoteService
{
    public function givenVote($pollId, $ip)
    {
        $userId = auth()->id();
        return Vote::query()
            ->where('poll_id', $pollId)
            ->when($userId, function ($query) use($userId, $ip) {
                $query->where('user_id', $userId)
                    ->orWhere('ip_address', $ip);
            })
            ->when(blank($userId), function ($query) use ($ip) {
                $query->where('ip_address', $ip);
            })
            ->first();
    }

    public function fetchVote($slug): array
    {
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
        $givenVote = $this->givenVote($poll->id, request()->ip());

        return [
            'poll' => $poll->only("id", "question", "created_at"),
            'options' => $options,
            'total_votes' => $totalVotes,
            'given_vote' => $givenVote,
        ];
    }
}
