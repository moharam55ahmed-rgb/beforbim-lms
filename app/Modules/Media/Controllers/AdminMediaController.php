<?php

namespace App\Modules\Media\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\Media\Models\MediaFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminMediaController extends Controller
{
    /**
     * Display media library items.
     */
    public function index(Request $request): View
    {
        $collection = $request->query('collection');

        $query = MediaFile::with('uploader')->latest();

        if ($collection && $collection !== 'all') {
            $query->where('collection_name', $collection);
        }

        $mediaFiles = $query->paginate(24);

        $collections = MediaFile::distinct()->pluck('collection_name');

        return view('admin.media.index', [
            'mediaFiles' => $mediaFiles,
            'collections' => $collections,
            'currentCollection' => $collection ?? 'all',
        ]);
    }

    /**
     * Upload a new media asset.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'collection_name' => 'nullable|string|max:50',
            'disk' => 'nullable|in:public,local',
        ]);

        $file = $request->file('file');
        $collection = $request->input('collection_name', 'general');
        $disk = $request->input('disk', 'public');

        $originalName = $file->getClientOriginalName();
        $safeName = Str::random(20).'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs("media/{$collection}", $safeName, $disk);

        $media = MediaFile::create([
            'model_type' => null,
            'model_id' => null,
            'collection_name' => $collection,
            'file_name' => $originalName,
            'file_path' => $path,
            'disk' => $disk,
            'mime_type' => $file->getClientMimeType() ?: 'application/octet-stream',
            'file_size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        AuditLog::log(
            module: 'Media',
            action: 'MEDIA_UPLOADED',
            actor: $request->user(),
            target: $media,
            oldValues: null,
            newValues: ['file_name' => $originalName, 'collection' => $collection, 'size' => $file->getSize()],
            reason: 'Media library upload'
        );

        return back()->with('status', 'تم رفع الملف بنجاح إلى مكتبة الوسائط.');
    }

    /**
     * Delete a media asset.
     */
    public function destroy(Request $request, MediaFile $media): RedirectResponse
    {
        if (Storage::disk($media->disk)->exists($media->file_path)) {
            Storage::disk($media->disk)->delete($media->file_path);
        }

        AuditLog::log(
            module: 'Media',
            action: 'MEDIA_DELETED',
            actor: $request->user(),
            target: $media,
            oldValues: ['file_name' => $media->file_name],
            newValues: null,
            reason: 'Media asset deleted by administrator'
        );

        $media->delete();

        return back()->with('status', 'تم حذف الملف من مكتبة الوسائط بنجاح.');
    }
}
