<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\PPDB\Contracts\PPDBServiceInterface;
use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use App\Modules\PPDB\Models\Registration;
use App\Modules\PPDB\Http\Requests\StoreRegistrationRequest;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPPDBController extends Controller
{
    public function __construct(
        private PPDBServiceInterface $ppdbService,
        private SystemServiceInterface $systemService
    ) {}

    public function index(): View
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        $tracks = [];

        if ($activeYear) {
            $tracks = AdmissionTrack::where('academic_year_id', $activeYear->id)
                ->where('is_active', true)
                ->get();
        }

        return view('ppdb::public.index', compact('activeYear', 'tracks'));
    }

    public function showForm(string $trackSlug): View|RedirectResponse
    {
        $track = AdmissionTrack::where('slug', $trackSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $academicYear = $track->academicYear;

        if (!$academicYear || !$academicYear->is_active) {
            return redirect()
                ->route('public.ppdb.index')
                ->with('error', 'Jalur pendaftaran tidak aktif karena tahun ajaran tidak aktif.');
        }

        $now = now();
        if ($academicYear->registration_open_at && $now->lt($academicYear->registration_open_at)) {
            return redirect()
                ->route('public.ppdb.index')
                ->with('error', 'Pendaftaran belum dibuka. Dibuka pada ' . $academicYear->registration_open_at->format('d-m-Y H:i'));
        }

        if ($academicYear->registration_close_at && $now->gt($academicYear->registration_close_at)) {
            return redirect()
                ->route('public.ppdb.index')
                ->with('error', 'Pendaftaran sudah ditutup.');
        }

        $fields = $track->fields()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $turnstileSiteKey = $this->systemService->getSetting('cloudflare.turnstile.site_key', '');

        return view('ppdb::public.form', compact('track', 'academicYear', 'fields', 'turnstileSiteKey'));
    }

    public function submit(string $trackSlug, StoreRegistrationRequest $request): RedirectResponse
    {
        $track = AdmissionTrack::where('slug', $trackSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $academicYear = $track->academicYear;

        if (!$academicYear || !$academicYear->is_active) {
            return redirect()
                ->route('public.ppdb.index')
                ->with('error', 'Tahun ajaran tidak aktif.');
        }

        $now = now();
        if ($academicYear->registration_open_at && $now->lt($academicYear->registration_open_at)) {
            return redirect()
                ->route('public.ppdb.index')
                ->with('error', 'Pendaftaran belum dibuka.');
        }

        if ($academicYear->registration_close_at && $now->gt($academicYear->registration_close_at)) {
            return redirect()
                ->route('public.ppdb.index')
                ->with('error', 'Pendaftaran sudah ditutup.');
        }

        $data = $request->validated();
        $data['track_id'] = $track->id;

        $registration = $this->ppdbService->submitRegistration($data);

        return redirect()
            ->route('public.ppdb.status')
            ->with('success', 'Pendaftaran berhasil dikirim. Nomor Pendaftaran Anda: ' . $registration->registration_number);
    }

    public function checkStatus(Request $request): View
    {
        $registration = null;
        $searched = false;

        if ($request->filled('registration_number')) {
            $searched = true;
            $regNum = trim($request->input('registration_number'));
            
            $registration = Registration::where('registration_number', $regNum)
                ->with(['academicYear', 'track', 'values.field', 'documents.field'])
                ->first();
        }

        return view('ppdb::public.status-check', compact('registration', 'searched'));
    }
}
