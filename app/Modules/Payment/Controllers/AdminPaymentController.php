<?php

namespace App\Modules\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * List all payments requiring administrative verification.
     */
    public function index(): View
    {
        $payments = Payment::where('status', 'REQUIRES_ADMIN_VERIFICATION')
            ->with(['order.user', 'order.items.course'])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Verify payment and activate student course enrollments.
     */
    public function verify(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $this->paymentService->adminVerifyPayment($payment, $request->user(), $validated['notes'] ?? null);

        return back()->with('success', 'تم تأكيد التحويل المالي وتفعيل اشتراك الطالب بنجاح.');
    }

    /**
     * Reject payment receipt.
     */
    public function reject(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5'],
        ]);

        $this->paymentService->adminRejectPayment($payment, $request->user(), $validated['reason']);

        return back()->with('success', 'تم رفض إيصال الدفع وإشعار الطالب.');
    }
}
