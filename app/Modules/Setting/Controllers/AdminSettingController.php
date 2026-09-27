<?php

namespace App\Modules\Setting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Setting\Services\CmsSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function __construct(
        protected CmsSettingService $settingService
    ) {}

    /**
     * Display CMS Settings management view.
     */
    public function index(): View
    {
        $groupedSettings = $this->settingService->getAllGrouped();

        return view('admin.settings.index', [
            'groupedSettings' => $groupedSettings,
        ]);
    }

    /**
     * Update CMS settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $submitted = $request->except(['_token', '_method']);

        $oldValues = [];
        $newValues = [];

        foreach ($submitted as $key => $val) {
            $oldValues[$key] = $this->settingService->get($key);
            $newValues[$key] = $val;
        }

        $this->settingService->bulkUpdate($submitted);

        AuditLog::log(
            module: 'Setting',
            action: 'CMS_SETTINGS_UPDATED',
            actor: $request->user(),
            target: null,
            oldValues: $oldValues,
            newValues: $newValues,
            reason: 'Administrative update of platform configurations'
        );

        return back()->with('status', 'تم حفظ وتحديث إعدادات المنصة بنجاح.');
    }
}
