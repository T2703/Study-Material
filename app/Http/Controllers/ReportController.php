<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->validate(['reason' => 'nullable|string|max:1000']);

        if ($request->user()->id === $user->id) {
            return back()->with('error', 'You cannot report yourself.');
        }

        Report::updateOrCreate(
            ['reporter_id' => $request->user()->id, 'reported_id' => $user->id],
            ['reason' => $request->reason]
        );

        return back()->with('message', 'User reported successfully.');
    }
}
