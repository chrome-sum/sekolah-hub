<?php

declare(strict_types=1);

namespace App\Modules\Theme\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Modules\Theme\Contracts\ThemeServiceInterface;
use App\Modules\CMS\Contracts\CMSServiceInterface;
use App\Modules\Gallery\Contracts\GalleryServiceInterface;
use App\Modules\PPDB\Contracts\PPDBServiceInterface;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function __construct(
        private ThemeServiceInterface $themeService,
        private CMSServiceInterface $cmsService,
        private GalleryServiceInterface $galleryService,
        private PPDBServiceInterface $ppdbService
    ) {}

    public function index(): View
    {
        $activeTheme = $this->themeService->getActiveTheme();
        $activeSections = $this->themeService->getHomepageSections();
        $themeSettings = $this->themeService->getThemeSettings($activeTheme);

        // Fetch data for specific sections if they are active
        $posts = collect();
        if (in_array('news', $activeSections)) {
            try {
                $posts = $this->cmsService->getPublishedPosts(3);
            } catch (\Exception $e) {
                // Fail-safe
            }
        }

        $albums = collect();
        if (in_array('gallery', $activeSections)) {
            try {
                $albums = $this->galleryService->getPublishedAlbums(3);
            } catch (\Exception $e) {
                // Fail-safe
            }
        }

        $activePPDBYear = null;
        $activePPDBTracks = collect();
        if (in_array('ppdb', $activeSections) || in_array('cta', $activeSections)) {
            try {
                $activePPDBYear = $this->ppdbService->getActiveAcademicYear();
                if ($activePPDBYear) {
                    $activePPDBTracks = $this->ppdbService->getActiveTracks($activePPDBYear->id);
                }
            } catch (\Exception $e) {
                // Fail-safe
            }
        }

        return view('homepage', compact(
            'activeTheme',
            'activeSections',
            'themeSettings',
            'posts',
            'albums',
            'activePPDBYear',
            'activePPDBTracks'
        ));
    }
}
