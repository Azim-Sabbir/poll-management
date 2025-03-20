<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PollResource extends ResourceCollection
{

    private function calculatePercentage(int $totalVotes, int $optionVotes): float
    {
        return $totalVotes > 0 ? number_format(($optionVotes / $totalVotes) * 100, 1) : 0;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($poll) {
            $totalVotes = $poll->options->sum('total_votes');
            $options = $poll->options->map(function ($option) use ($totalVotes) {
                return [
                    'id' => $option->id,
                    'title' => $option->title,
                    'votes' => $option->total_votes,
                    'percentage' => $this->calculatePercentage($totalVotes, $option->total_votes)
                ];
            });
            return [
                'poll' => $poll->only("id", "question", "slug", "created_at"),
                'options' => $options,
                'total_votes' => $totalVotes,
                'given_vote' => null,
            ];
        })->toArray();
    }
}
