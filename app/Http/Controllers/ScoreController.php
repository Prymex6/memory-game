<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index()
    {
        return Score::orderBy('moves')->orderBy('time_seconds')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'player_name' => 'required|string|max:255',
            'moves' => 'required|integer',
            'time_seconds' => 'required|integer',
        ]);

        return Score::create($validated);
    }
}
