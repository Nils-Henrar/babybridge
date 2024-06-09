<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\ChildTutor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DailyJournalController extends Controller
{

    public function showByUser($userId, $date)
    {
        $childrenIds = ChildTutor::where('user_id', $userId)->pluck('child_id'); // Récupère les IDs des enfants dont l'utilisateur est le tuteur
        Log::info('Children IDs: ' . $childrenIds);
        $entries = collect();

        foreach ($childrenIds as $childId) {
            Log::info('Child ID: ' . $childId);
            $child = $this->getChildData($childId, $date);
            if ($child) {
                $entries = $entries->concat($this->formatChildEntries($child, $date));
            }
        }

        $sortedEntries = $entries->sortBy('time', SORT_REGULAR, true);
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
                },
                'attendances' => function ($query) use ($date) {
                    $query->whereDate('attendance_date', '=', $date);
                }
            ])->first();
    }    

    protected function formatChildEntries($child, $date)
{
    $entries = collect();
    $relations = ['naps', 'childMeals', 'activityChildren', 'photos', 'diaperChanges', 'attendances'];

    foreach ($relations as $relation) {
        $child->$relation->each(function ($item) use ($entries, $date, $relation) {
            $field = $this->getDateFieldForRelation($relation); 
            if (Carbon::parse($item->$field)->format('Y-m-d') == $date) {
                $formattedEntries = $item->formatForJournal();
                if (is_array($formattedEntries)) {
                    foreach ($formattedEntries as $entry) {
                        $entries->push($entry);
                    }
                } else {
                    $entries->push($formattedEntries);
                }
            }
        });
    }

    Log::info('Entries: ' . $entries);
    return $entries;
}

    protected function getDateFieldForRelation($relation)
    {
        switch ($relation) {
            case 'naps':
                return 'started_at';
            case 'childMeals':
                return 'meal_time';
            case 'activityChildren':
                return 'performed_at';
            case 'photos':
                return 'taken_at';
            case 'diaperChanges':
                return 'happened_at';
            case 'attendances':
                return 'attendance_date';
            default:
                throw new \Exception("Invalid relation: $relation");
        } 
    }

}
