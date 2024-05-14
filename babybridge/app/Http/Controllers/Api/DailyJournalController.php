<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use App\Http\Resources\ChildDailyJournalResource;
use App\Models\ChildTutor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DailyJournalController extends Controller
{
    public function show($childId, $date)
    {
        $child = $this->getChildData($childId, $date);
        return new ChildDailyJournalResource($child);
    }

    public function showByUser($userId, $date)
    {
        $childrenIds = ChildTutor::where('user_id', $userId)->pluck('child_id');
        Log::info('Children IDs: ' . $childrenIds);
        $entries = collect();

        foreach ($childrenIds as $childId) {
            Log::info('Child ID: ' . $childId);
            $child = $this->getChildData($childId, $date);
            if ($child) {
                $entries = $entries->concat($this->formatChildEntries($child, $date));
            }
        }

        $sortedEntries = $entries->sortBy('time');
        return response()->json($sortedEntries->values()->all());
    }

    protected function getChildData($childId, $date)
    {
        return Child::where('id', $childId)
            ->with([
                'naps' => function ($query) use ($date) {
                    $query->whereDate('started_at', '=', $date);
                },
                'childMeals' => function ($query) use ($date) {
                    $query->whereDate('meal_time', '=', $date);
                },
                'activityChildren' => function ($query) use ($date) {
                    $query->whereDate('performed_at', '=', $date);
                },
                'photos' => function ($query) use ($date) {
                    $query->whereDate('taken_at', '=', $date);
                },
                'diaperChanges' => function ($query) use ($date) {
                    $query->whereDate('happened_at', '=', $date);
                }
            ])->first();
    }

    protected function formatChildEntries($child, $date)
    {
        $entries = collect();
        foreach (['naps', 'childMeals', 'activityChildren', 'photos', 'diaperChanges'] as $relation) {
            $child->$relation->each(function ($item) use ($entries, $date) {
                if (Carbon::parse($item->started_at)->format('Y-m-d') == $date) {
                    $entries->push($item->formatForJournal());
                }

                if (Carbon::parse($item->meal_time)->format('Y-m-d') == $date) {
                    $entries->push($item->formatForJournal());
                }

                if (Carbon::parse($item->performed_at)->format('Y-m-d') == $date) {
                    $entries->push($item->formatForJournal());
                }

                if (Carbon::parse($item->taken_at)->format('Y-m-d') == $date) {
                    $entries->push($item->formatForJournal());
                }

                if (Carbon::parse($item->happened_at)->format('Y-m-d') == $date) {
                    $entries->push($item->formatForJournal());
                }
            });
        }
        return $entries;
    }
}
