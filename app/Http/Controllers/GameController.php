<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitScoreRequest;
use App\Models\HistorySubmitScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function submitScore(SubmitScoreRequest $request)
    {
        $data = $request->validated();

        $existingScore = HistorySubmitScore::where('user_id', $data['user_id'])
            ->where('level', $data['level'])
            ->first();

        if ($existingScore) {
            if ($data['score'] > $existingScore->score) {
                $existingScore->update([
                    'score' => $data['score'
                ]]);
            }
        } else {
            HistorySubmitScore::create($data);
        }

        return response()->json([
            'status' => true,
            'message' => 'Score submitted successfully!'
        ]);
    }

    public function leaderboard(Request $request)
    {
        $leaderboard = Cache::remember('leaderboard_' . $request->page, 60, function () {
            return HistorySubmitScore::select('user_id', DB::raw('MAX(level) as last_level'), DB::raw('SUM(score) as total_score'))
                ->groupBy('user_id')
                ->orderBy('total_score', 'desc')
                ->with('user:id,name')
                ->paginate(10);
        });

        if ($request->has('username')) {
            $user = User::where('name', $request->username)->first();

            if ($user) {
                $userRank = HistorySubmitScore::where('user_id', $user->id)
                    ->select(DB::raw('MAX(level) as last_level'), DB::raw('SUM(score) as total_score'))
                    ->first();

                return response()->json([
                    'name' => $user->name,
                    'last_level' => $userRank->last_level,
                    'total_score' => $userRank->total_score,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json($leaderboard);
    }
}
