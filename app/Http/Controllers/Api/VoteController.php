<?php

namespace App\Http\Controllers\Api;

use App\Events\VoteUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoteRequest;
use App\Models\Poll;
use App\Models\Vote;
use App\services\VoteService;
use Illuminate\Http\Request;

class VoteController extends ApiBaseController
{
    public function __construct(private VoteService $voteService)
    {}

    public function show($slug)
    {
        try {
            $data = $this->voteService->fetchVote($slug);
            return $this->successResponse($data, '');
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage());
        }
    }

    public function vote(VoteRequest $request, $pollId)
    {
        try {
//            $isAlreadyVoted = filled(
//                $this->voteService->givenVote($pollId, $ipAddress)
//            );

            $isAlreadyVoted = $request->cookie("voted_poll_$pollId");

            $this->voteService->handleVote(
                $request,
                $pollId,
                $isAlreadyVoted
            );

            return $this->successResponse(
                null,
                'Your vote has been recorded.',
                withCookie: cookie("voted_poll_$pollId", true, (60*24)*7)
            );
        } catch (\Exception $e) {
            return $this->failedResponse($e->getMessage());
        }
    }
}
