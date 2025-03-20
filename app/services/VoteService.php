<?php

namespace App\services;

use App\Events\VoteUpdated;
use App\Exceptions\AlreadyVotedException;
use App\Models\Poll;
use App\Models\Vote;

class VoteService
{
    /**
     * check if a vote is already given by the user or ip
     *
     * @param $pollId
     * @param $ip
     * @return Vote|null
     */
    public function givenVote($pollId, $ip): ?Vote
    {
        $isAlreadyVoted = request()->cookie("voted_poll_$pollId");

        if (!$isAlreadyVoted) return null;

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
            ->latest()->first();
    }

    /**
     * fetch a vote against specific slug
     *
     * @param $slug
     * @return array
     */
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
                'percentage' => $this->calculatePercentage($totalVotes, $option->total_votes)
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

    /**
     * Calculate the percentage of votes for an option
     *
     * @param int $totalVotes
     * @param int $optionVotes
     * @return float
     */
    private function calculatePercentage(int $totalVotes, int $optionVotes): float
    {
        return $totalVotes > 0 ? number_format(($optionVotes / $totalVotes) * 100, 1) : 0;
    }


    /**
     * handle vote after a vote is received
     *
     * @param $request
     * @param $pollId
     * @param $isAlreadyVoted
     * @return void
     * @throws AlreadyVotedException
     */
    public function handleVote($request, $pollId, $isAlreadyVoted): void
    {
        $poll = Poll::query()->findOrFail($pollId);

        /*if already voted, change the vote*/
        if ($isAlreadyVoted) {
            $this->updateVote($request, $poll);
            return;
        }

        Vote::create([
            'poll_id' => $poll->id,
            'option_id' => $request->option_id,
            'user_id' => auth()->id() ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => getUserAgent($request),
        ]);

        $this->broadcast($poll);
    }

    /**
     * update the vote if already voted
     *
     * @param $request
     * @param $poll
     * @return void
     * @throws AlreadyVotedException
     */
    private function updateVote($request, $poll): void
    {
        $vote = $this->givenVote($poll->id, $request->ip());

        if ($vote->option_id == $request->option_id) {
            throw new AlreadyVotedException();
        }

        $vote->update([
            'option_id' => $request->option_id,
            'user_id' => auth()->id() ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => getUserAgent($request),
        ]);

        $this->broadcast($poll);
    }

    private function broadcast($poll): void
    {
        $payload = $this->fetchVote($poll->slug);
        broadcast(new VoteUpdated(
            $payload,
            $poll->id
        ))->toOthers();
    }
}
