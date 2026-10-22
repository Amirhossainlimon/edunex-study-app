<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $completionPercent = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        $upcomingExam = $user->exams()
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->first();

        $weekStart = now()->startOfDay()->subDays(6);
        $sessionMinutesByDate = $user->studySessions()
            ->whereDate('date', '>=', $weekStart->toDateString())
            ->whereDate('date', '<=', today()->toDateString())
            ->get(['date', 'duration_minutes'])
            ->groupBy(fn ($session) => $session->date->format('Y-m-d'))
            ->map(fn ($sessions) => (int) $sessions->sum('duration_minutes'));

        $weeklyStudy = collect(range(0, 6))->map(function (int $offset) use ($weekStart, $sessionMinutesByDate): array {
            $date = $weekStart->copy()->addDays($offset);
            return [
                'label' => $date->format('D'),
                'date' => $date->toDateString(),
                'minutes' => (int) ($sessionMinutesByDate[$date->toDateString()] ?? 0),
            ];
        });

        $maxWeeklyMinutes = max(1, (int) $weeklyStudy->max('minutes'));
        $totalStudyMinutesThisWeek = (int) $weeklyStudy->sum('minutes');

        $tasks = $user->tasks()
            ->orderByRaw("CASE status WHEN 'todo' THEN 0 WHEN 'in_progress' THEN 1 ELSE 2 END")
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->limit(6)
            ->get();

        $totalExams = $user->exams()->count();
        $totalSessions = $user->studySessions()->count();

        return view('dashboard', compact(
            'totalTasks', 'completedTasks', 'completionPercent', 'upcomingExam',
            'weeklyStudy', 'maxWeeklyMinutes', 'totalStudyMinutesThisWeek',
            'tasks', 'totalExams', 'totalSessions'
        ));
    }
}
