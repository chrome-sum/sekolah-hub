<?php

declare(strict_types=1);

namespace App\Modules\Theme\Services;

use App\Modules\Theme\Contracts\ThemeServiceInterface;
use App\Modules\System\Contracts\SystemServiceInterface;
use Illuminate\Support\Facades\File;

class ThemeService implements ThemeServiceInterface
{
    public function __construct(
        private SystemServiceInterface $systemService
    ) {}

    public function getAvailableThemes(): array
    {
        $themesPath = base_path('themes');
        if (!File::isDirectory($themesPath)) {
            return [];
        }

        $directories = File::directories($themesPath);
        $themes = [];

        foreach ($directories as $directory) {
            $dirName = basename($directory);
            $themeJsonPath = $directory . '/theme.json';

            if (File::exists($themeJsonPath)) {
                try {
                    $metadata = json_decode(File::get($themeJsonPath), true);
                    if (is_array($metadata)) {
                        $themes[] = [
                            'directory' => $dirName,
                            'name' => $metadata['name'] ?? $dirName,
                            'version' => $metadata['version'] ?? '1.0.0',
                            'author' => $metadata['author'] ?? 'Unknown',
                            'sections' => $metadata['sections'] ?? [],
                            'screenshot' => $this->getThemeScreenshotUrl($dirName),
                        ];
                    }
                } catch (\Exception $e) {
                    // Ignore corrupted theme.json files
                }
            }
        }

        return $themes;
    }

    public function getActiveTheme(): string
    {
        try {
            return $this->systemService->getSetting('theme.active', 'school-classic');
        } catch (\Exception $e) {
            return 'school-classic';
        }
    }

    public function setActiveTheme(string $themeName): void
    {
        // Verify if theme directory exists before setting active
        $themePath = base_path('themes/' . $themeName);
        if (File::isDirectory($themePath)) {
            try {
                $this->systemService->setSetting('theme.active', $themeName);
            } catch (\Exception $e) {
                // Fail-safe
            }
        }
    }

    public function getHomepageSections(): array
    {
        try {
            $sectionsJson = $this->systemService->getSetting('theme.homepage_sections');
            if ($sectionsJson) {
                $sections = json_decode($sectionsJson, true);
                if (is_array($sections)) {
                    return $sections;
                }
            }
        } catch (\Exception $e) {
            // Fallback if settings table does not exist
        }

        // Fallback to theme.json sections of active theme
        try {
            $activeTheme = $this->getActiveTheme();
            $themeJsonPath = base_path('themes/' . $activeTheme . '/theme.json');
            if (File::exists($themeJsonPath)) {
                $metadata = json_decode(File::get($themeJsonPath), true);
                if (isset($metadata['sections']) && is_array($metadata['sections'])) {
                    return $metadata['sections'];
                }
            }
        } catch (\Exception $e) {
            // Fallback
        }

        return ['hero', 'announcement', 'news', 'gallery', 'ppdb', 'contact'];
    }

    public function setHomepageSections(array $sections): void
    {
        try {
            $this->systemService->setSetting('theme.homepage_sections', json_encode(array_values($sections)));
        } catch (\Exception $e) {
            // Fail-safe
        }
    }

    public function getThemeSettings(string $themeName): array
    {
        $prefix = "theme.{$themeName}.";
        
        try {
            // Find settings in database with this theme prefix
            $settings = \App\Modules\System\Models\Setting::where('key', 'like', $prefix . '%')->get();
            
            $settingsMap = [];
            foreach ($settings as $setting) {
                $keyWithoutPrefix = str_replace($prefix, '', $setting->key);
                $settingsMap[$keyWithoutPrefix] = $setting->value;
            }

            return $settingsMap;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function updateThemeSettings(string $themeName, array $settings): void
    {
        foreach ($settings as $key => $value) {
            $settingKey = "theme.{$themeName}.{$key}";
            $this->systemService->setSetting($settingKey, $value !== null ? (string)$value : null);
        }
    }

    private function getThemeScreenshotUrl(string $themeName): ?string
    {
        // Look for screenshot in public/vendor/themes/{theme-name}/screenshot.png
        $publicPath = "vendor/themes/{$themeName}/screenshot.png";
        if (File::exists(public_path($publicPath))) {
            return '/' . $publicPath;
        }

        // Fallback: check if we have a screenshot inside themes folder (though it won't be public direct url)
        if (File::exists(base_path("themes/{$themeName}/screenshots/screenshot.png"))) {
            // We'll copy it to public/vendor/themes/{theme-name}/screenshot.png to expose it
            try {
                $destDir = public_path("vendor/themes/{$themeName}");
                if (!File::isDirectory($destDir)) {
                    File::makeDirectory($destDir, 0755, true);
                }
                File::copy(
                    base_path("themes/{$themeName}/screenshots/screenshot.png"),
                    $destDir . '/screenshot.png'
                );
                return '/' . $publicPath;
            } catch (\Exception $e) {
                // Ignore copying errors
            }
        }

        return null;
    }
}
