<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Section;

class AttendanceController extends Controller
{
    public function getSectionAttendances($sectionId, $date)
{
    $section = Section::with(['childSections.child'])->find($sectionId);

    if (!$section) {
        return response()->json(['message' => 'Section not found'], 404);
    }

    $attendances = Attendance::whereDate('attendance_date', $date)
                             ->whereIn('child_id', $section->childSections->pluck('child_id'))
                             ->get();

    return response()->json($attendances);
}


public function storeOrUpdateAttendance(Request $request)
{
    Log::info('Attendance Data:', $request->all());

    $validator = Validator::make($request->all(), [
        'child_id' => 'required|integer|exists:children,id',
        'attendance_date' => 'required|date',
        'arrival_time' => 'sometimes|nullable|date_format:H:i',
        'departure_time' => 'sometimes|nullable|date_format:H:i',
        'notes' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        $validator->errors()->add('message', 'Validation error');

        return response()->json($validator->errors(), 422);
    }

    $attendance = Attendance::updateOrCreate(
        [
            'child_id' => $request->child_id,
            'attendance_date' => $request->attendance_date
        ],
        [
            'arrival_time' => $request->arrival_time ?: null, // set null if empty
            'departure_time' => $request->departure_time ?: null, // set null if empty
            'notes' => $request->notes,
        ]
    );

    return response()->json([
        'message' => 'Attendance record updated successfully',
        'attendance' => $attendance,
    ]);
}

}
