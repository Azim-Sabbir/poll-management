<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::query()->select('id', 'question', 'created_at', 'slug')->get();

        return view('polls.index', compact('polls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array',
        ]);


        $poll = Poll::create([
            'question' => $request->question,
            'slug' => \Str::slug($request->question),
        ]);

        $poll->options()->createMany(array_map(function ($option) {
            return ['title' => $option];
        }, $request->options));

        return redirect()->route('polls.index');
    }

    public function show(Poll $poll)
    {
        $poll->load(['options' => function ($query) {
            $query->withCount('votes as total_votes');
        }]);

        return view('polls.show', compact('poll'));
    }
}
