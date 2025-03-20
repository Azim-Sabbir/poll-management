<?php

namespace App\Http\Controllers;

use App\Http\Requests\PollRequest;
use App\Http\Requests\PollUpdateRequest;
use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::query()->select('id', 'question', 'created_at', 'slug')->get();

        return view('polls.index', compact('polls'));
    }

    public function store(PollRequest $request)
    {
        try {
            $poll = Poll::create([
                'question' => $request->question,
                'slug' => \Str::slug($request->question),
            ]);

            $poll->options()->createMany(array_map(function ($option) {
                return ['title' => $option];
            }, $request->options));
            return back()->with('success', 'Poll created successfully');
        } catch (\Exception $exception) {
            return back()->with('error', 'Something went wrong');
        }
    }

    public function show(Poll $poll)
    {
        $poll->load(['options' => function ($query) {
            $query->withCount('votes as total_votes');
        }]);

        return view('polls.show', compact('poll'));
    }

    public function edit(Poll $poll)
    {
        $poll->load('options');

        return view('polls.edit', compact('poll'));
    }

    public function update(Poll $poll, PollUpdateRequest $request)
    {
        try {
            $poll->update([
                'question' => $request->question,
            ]);
            return back()->with('success', 'Poll title updated successfully');
        } catch (\Exception $exception) {
            return back()->with('error', 'Something went wrong');
        }
    }
}
