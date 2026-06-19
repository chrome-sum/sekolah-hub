<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\PPDB\Contracts\PPDBServiceInterface;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\Registration;
use App\Modules\PPDB\Models\RegistrationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RegistrationController extends Controller
{
    public function __construct(
        private PPDBServiceInterface $ppdbService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('ppdb.manage');

        $query = Registration::with(['academicYear', 'track'])
            ->orderBy('id', 'desc');

        if ($request->filled('track_id')) {
            $query->where('track_id', $request->input('track_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $registrations = $query->paginate(15);
        $tracks = AdmissionTrack::orderBy('id', 'desc')->get();

        return view('ppdb::admin.registrations.index', compact('registrations', 'tracks'));
    }

    public function show(Registration $registration): View
    {
        Gate::authorize('ppdb.manage');

        $registration->load([
            'academicYear',
            'track',
            'values.field',
            'documents.field.track'
        ]);

        return view('ppdb::admin.registrations.show', compact('registration'));
    }

    public function updateStatus(Registration $registration, Request $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:draft,submitted,under_review,verified,accepted,rejected,withdrawn'],
            'notes' => ['nullable', 'string'],
        ]);

        $this->ppdbService->updateRegistrationStatus(
            $registration,
            $validated['status'],
            $validated['notes'] ?? null
        );

        return redirect()
            ->route('admin.ppdb.registrations.show', $registration->id)
            ->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function verifyDocument(RegistrationDocument $document, Request $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        $validated = $request->validate([
            'verification_status' => ['required', 'string', 'in:pending,approved,rejected'],
            'verification_notes' => ['nullable', 'string'],
        ]);

        $this->ppdbService->verifyDocument(
            $document,
            $validated['verification_status'],
            $validated['verification_notes'] ?? null
        );

        return redirect()
            ->route('admin.ppdb.registrations.show', $document->registration_id)
            ->with('success', 'Verifikasi dokumen berhasil diperbarui.');
    }

    public function downloadDocument(RegistrationDocument $document)
    {
        Gate::authorize('ppdb.manage');

        if (!Storage::disk('local')->exists($document->path)) {
            abort(404, 'File tidak ditemukan di penyimpanan.');
        }

        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    public function export(Request $request)
    {
        Gate::authorize('ppdb.manage');

        $request->validate([
            'track_id' => ['required', 'exists:admission_tracks,id'],
        ]);

        $track = AdmissionTrack::findOrFail($request->input('track_id'));

        return $this->ppdbService->exportRegistrations($track);
    }
}
