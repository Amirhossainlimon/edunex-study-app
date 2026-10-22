<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudySessionRequest;
use App\Http\Requests\UpdateStudySessionRequest;
use App\Models\StudySession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudySessionController extends Controller
{
    public function index(Request $request): View
    {
        $studySessions = $request->user()->studySessions()
            ->orderByDesc('date')->orderByDesc('created_at')->paginate(12)->withQueryString();
        $totalMinutes = (int) $request->user()->studySessions()->sum('duration_minutes');
        return view('study-sessions.index', compact('studySessions', 'totalMinutes'));
    }

    public function create(): View
    {
        return view('study-sessions.create', ['studySession' => new StudySession(['date' => today()])]);
    }

    public function store(StoreStudySessionRequest $request): RedirectResponse|JsonResponse
    {
        $studySession = $request->user()->studySessions()->create($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Study session saved.',
                'study_session' => $studySession,
            ], 201);
        }

        return redirect()->route('study-sessions.index')->with('success', 'Study session saved.');
    }

    public function edit(StudySession $study_session): View
    {
        $studySession = auth()->user()->studySessions()->findOrFail($study_session->getKey());
        return view('study-sessions.edit', compact('studySession'));
    }

    public function update(UpdateStudySessionRequest $request, StudySession $study_session): RedirectResponse
    {
        $ownedSession = $request->user()->studySessions()->findOrFail($study_session->getKey());
        $ownedSession->update($request->validated());
        return redirect()->route('study-sessions.index')->with('success', 'Study session updated.');
    }

    public function destroy(Request $request, StudySession $study_session): RedirectResponse
    {
        $request->user()->studySessions()->findOrFail($study_session->getKey())->delete();
        return redirect()->route('study-sessions.index')->with('success', 'Study session deleted.');
    }
}
