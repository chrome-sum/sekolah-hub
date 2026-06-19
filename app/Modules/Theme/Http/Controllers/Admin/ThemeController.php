<?php

declare(strict_types=1);

namespace App\Modules\Theme\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Theme\Contracts\ThemeServiceInterface;
use App\Modules\Media\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function __construct(
        private ThemeServiceInterface $themeService
    ) {}

    public function index(): View
    {
        Gate::authorize('settings.manage');

        $themes = $this->themeService->getAvailableThemes();
        $activeTheme = $this->themeService->getActiveTheme();
        $activeSections = $this->themeService->getHomepageSections();
        $themeSettings = $this->themeService->getThemeSettings($activeTheme);

        // Get all predefined sections defined in the active theme
        // If theme directory is invalid or has no sections, use fallback
        $allSections = ['hero', 'announcement', 'news', 'gallery', 'ppdb', 'contact', 'cta'];
        foreach ($themes as $theme) {
            if ($theme['directory'] === $activeTheme) {
                $allSections = $theme['sections'];
                break;
            }
        }

        // Fetch image files from Media module for selection dropdowns
        $mediaList = Media::where('mime_type', 'like', 'image/%')->latest()->get();

        return view('theme::admin.index', compact(
            'themes',
            'activeTheme',
            'activeSections',
            'allSections',
            'themeSettings',
            'mediaList'
        ));
    }

    public function activate(Request $request): RedirectResponse
    {
        Gate::authorize('settings.manage');

        $validated = $request->validate([
            'theme' => ['required', 'string'],
        ]);

        $this->themeService->setActiveTheme($validated['theme']);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Tema aktif berhasil diperbarui.');
    }

    public function updateSections(Request $request): RedirectResponse
    {
        Gate::authorize('settings.manage');

        $validated = $request->validate([
            'sections' => ['nullable', 'array'],
        ]);

        $sections = $validated['sections'] ?? [];

        $this->themeService->setHomepageSections($sections);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Susunan section beranda berhasil diperbarui.');
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        Gate::authorize('settings.manage');

        $validated = $request->validate([
            'settings' => ['required', 'array'],
        ]);

        $activeTheme = $this->themeService->getActiveTheme();
        $this->themeService->updateThemeSettings($activeTheme, $validated['settings']);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Konfigurasi tema berhasil diperbarui.');
    }
}
