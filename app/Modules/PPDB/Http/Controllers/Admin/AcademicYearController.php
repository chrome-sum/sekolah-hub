<?php

declare(strict_types=1);

namespace App\Modules\PPDB\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\PPDB\Contracts\PPDBServiceInterface;
use App\Modules\PPDB\Http\Requests\StoreAcademicYearRequest;
use App\Modules\PPDB\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function __construct(
        private PPDBServiceInterface $ppdbService
    ) {}

    public function index(): View
    {
        Gate::authorize('ppdb.manage');
        $academicYears = AcademicYear::with(['creator', 'updater'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('ppdb::admin.academic-years.index', compact('academicYears'));
    }

    public function create(): View
    {
        Gate::authorize('ppdb.manage');
        return view('ppdb::admin.academic-years.create');
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');
        
        $data = $request->validated();
        $this->ppdbService->createAcademicYear($data);

        return redirect()
            ->route('admin.ppdb.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        Gate::authorize('ppdb.manage');
        return view('ppdb::admin.academic-years.edit', compact('academicYear'));
    }

    public function update(AcademicYear $academicYear, StoreAcademicYearRequest $request): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        $data = $request->validated();
        $this->ppdbService->updateAcademicYear($academicYear, $data);

        return redirect()
            ->route('admin.ppdb.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        Gate::authorize('ppdb.manage');

        if ($academicYear->is_active) {
            return redirect()
                ->route('admin.ppdb.academic-years.index')
                ->with('error', 'Tahun ajaran aktif tidak dapat dihapus.');
        }

        $academicYear->delete();

        return redirect()
            ->route('admin.ppdb.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
