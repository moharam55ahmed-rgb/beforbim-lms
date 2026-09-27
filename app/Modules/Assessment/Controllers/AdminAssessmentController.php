<?php

namespace App\Modules\Assessment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Assessment\Models\Assessment;
use App\Modules\Assessment\Models\AssessmentQuestion;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Certificate\Models\Certificate;
use Illuminate\Http\Request;

class AdminAssessmentController extends Controller
{
    /**
     * Admin view: list and review all assessments across courses.
     */
    public function index(Request $request)
    {
        $query = Assessment::with(['course'])->withCount(['questions', 'attempts']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $assessments = $query->latest()->paginate(15);

        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * Admin view: manage question bank with category, difficulty, and tags filters.
     */
    public function questionBank(Request $request)
    {
        $query = AssessmentQuestion::with('assessment.course');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('difficulty_level')) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('question_text_ar', 'like', $search)
                  ->orWhere('question_text_en', 'like', $search);
            });
        }

        $questions = $query->latest()->paginate(20);
        $categories = AssessmentQuestion::whereNotNull('category')->distinct()->pluck('category');

        return view('admin.assessments.questions', compact('questions', 'categories'));
    }

    /**
     * Store a new question in the question bank.
     */
    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'question_text_ar' => 'required|string',
            'question_text_en' => 'nullable|string',
            'question_type' => 'required|in:single_choice,multiple_choice,numerical,essay',
            'category' => 'nullable|string|max:100',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'tags' => 'nullable|array',
            'points' => 'required|numeric|min:0.5',
            'options' => 'nullable|array',
            'explanation_ar' => 'nullable|string',
        ]);

        $question = AssessmentQuestion::create($validated);

        AuditLog::log('AssessmentQuestion', 'QUESTION_CREATED', auth()->user(), $question, null, [
            'category' => $question->category,
            'difficulty' => $question->difficulty_level,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة السؤال إلى بنك الأسئلة بنجاح.');
    }

    /**
     * Update an existing question.
     */
    public function updateQuestion(Request $request, AssessmentQuestion $question)
    {
        $validated = $request->validate([
            'question_text_ar' => 'required|string',
            'question_text_en' => 'nullable|string',
            'question_type' => 'required|in:single_choice,multiple_choice,numerical,essay',
            'category' => 'nullable|string|max:100',
            'difficulty_level' => 'required|in:easy,medium,hard',
            'tags' => 'nullable|array',
            'points' => 'required|numeric|min:0.5',
            'options' => 'nullable|array',
            'explanation_ar' => 'nullable|string',
        ]);

        $question->update($validated);

        AuditLog::log('AssessmentQuestion', 'QUESTION_UPDATED', auth()->user(), $question);

        return redirect()->back()->with('success', 'تم تحديث بيانات السؤال بنجاح.');
    }

    /**
     * Delete a question from the question bank.
     */
    public function destroyQuestion(AssessmentQuestion $question)
    {
        AuditLog::log('AssessmentQuestion', 'QUESTION_DELETED', auth()->user(), $question);

        $question->delete();

        return redirect()->back()->with('success', 'تم حذف السؤال من بنك الأسئلة.');
    }

    /**
     * Admin view: review all certificates with verification and revocation controls.
     */
    public function certificates(Request $request)
    {
        $query = Certificate::with(['user', 'course', 'template']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', $search)
                  ->orWhere('verification_code', 'like', $search)
                  ->orWhere('student_name_snapshot', 'like', $search)
                  ->orWhere('course_title_snapshot_ar', 'like', $search);
            });
        }

        $certificates = $query->latest()->paginate(15);
        $totalActive = Certificate::where('status', 'active')->count();
        $totalRevoked = Certificate::where('status', 'revoked')->count();

        return view('admin.assessments.certificates', compact('certificates', 'totalActive', 'totalRevoked'));
    }

    /**
     * Revoke a certificate.
     */
    public function revokeCertificate(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:5|max:500',
        ]);

        $certificate->update([
            'status' => 'revoked',
            'is_revoked' => true,
            'revocation_reason' => $validated['reason'],
        ]);

        AuditLog::log('Certificate', 'CERTIFICATE_REVOKED', auth()->user(), $certificate, null, [
            'reason' => $validated['reason'],
            'student_id' => $certificate->user_id,
        ]);

        return redirect()->back()->with('success', 'تم سحب وإلغاء الشهادة رسمياً.');
    }

    /**
     * Reissue / reactivate a certificate.
     */
    public function reissueCertificate(Request $request, Certificate $certificate)
    {
        $certificate->update([
            'status' => 'active',
            'is_revoked' => false,
            'revocation_reason' => null,
            'issued_at' => now(),
        ]);

        AuditLog::log('Certificate', 'CERTIFICATE_REISSUED', auth()->user(), $certificate, null, [
            'student_id' => $certificate->user_id,
        ]);

        return redirect()->back()->with('success', 'تمت إعادة إصدار وتفعيل الشهادة بنجاح.');
    }
}
