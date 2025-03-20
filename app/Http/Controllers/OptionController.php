<?php

namespace App\Http\Controllers;

use App\Http\Requests\OptionRequest;
use App\Models\Option;
use App\Models\Poll;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function removeOption(Option $option)
    {
        $option->votes()->delete();
        $option->delete();

        return back()->with('success', 'Option removed successfully');
    }

    public function createOption(Poll $poll, OptionRequest $request)
    {
        $poll->options()->create([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Option created successfully');
    }

    public function updatePollOption(Option $option, OptionRequest $request)
    {
        $option->update([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Option updated successfully');
    }
}
