<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Models\Exam;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(Request $request): View
    {
        $exams = $request->user()->exams()->orderBy('exam_date')->paginate(12)->withQueryString();
        return view('exams.index', compact('exams'));
    }

    public function create(): View
    {
        return view('exams.create', ['exam' => new Exam()]);
    }

    public function store(StoreExamRequest $request): RedirectResponse
    {
        $request->user()->exams()->create($request->validated());
        return redirect()->route('exams.index')->with('success', 'Exam added successfully.');
    }

    public function edit(Exam $exam): View
    {
        $exam = auth()->user()->exams()->findOrFail($exam->getKey());
        return view('exams.edit', compact('exam'));
    }

    public function update(UpdateExamRequest $request, Exam $exam): RedirectResponse
    {
        $ownedExam = $request->user()->exams()->findOrFail($exam->getKey());
        $ownedExam->update($request->validated());
        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Request $request, Exam $exam): RedirectResponse
    {
        $request->user()->exams()->findOrFail($exam->getKey())->delete();
        return redirect()->route('exams.index')->with('success', 'Exam deleted.');
    }
}
