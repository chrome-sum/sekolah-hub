<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\PPDB\Models\AcademicYear;
use App\Modules\PPDB\Models\AdmissionTrack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdmissionTrackController extends Controller
{
    public function index(): View
    {
        Gate::authorize('ppdb.manage');
        $tracks = AdmissionTrack::with('academicYear')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('ppdb::admin.tracks.index', compact('tracks'));
    }

    public function create(): View
    {
        Gate::authorize('ppdb.manage');
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        return view('ppdb::admin.tracks.create', compact('academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;
        while (AdmissionTrack::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        AdmissionTrack::create([
            'academic_year_id' => $validated['academic_year_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'quota' => $validated['quota'] ? (int) $validated['quota'] : null,
            'description' => $validated['description'] ?? null,
            'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : true,
        ]);

        return redirect()
            ->route('admin.ppdb.tracks.index')
            ->with('success', 'Jalur pendaftaran berhasil ditambahkan.');
    }

    public function edit(AdmissionTrack $track): View
    {
        Gate::authorize('ppdb.manage');
        $academicYears = AcademicYear::orderBy('id', 'desc')->get();
        return view('ppdb::admin.tracks.edit', compact('track', 'academicYears'));
    }

    public function update(AdmissionTrack $track, Request $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'quota' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = $track->slug;
        if ($track->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $count = 1;
            while (AdmissionTrack::where('slug', $slug)->where('id', '!=', $track->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        }

        $track->update([
            'academic_year_id' => $validated['academic_year_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'quota' => $validated['quota'] ? (int) $validated['quota'] : null,
            'description' => $validated['description'] ?? null,
            'is_active' => isset($validated['is_active']) ? (bool) $validated['is_active'] : true,
        ]);

        return redirect()
            ->route('admin.ppdb.tracks.index')
            ->with('success', 'Jalur pendaftaran berhasil diperbarui.');
    }

    public function destroy(AdmissionTrack $track): RedirectResponse
    {
        Gate::authorize('ppdb.manage');
        $track->delete();

        return redirect()
            ->route('admin.ppdb.tracks.index')
            ->with('success', 'Jalur pendaftaran berhasil dihapus.');
    }
}
