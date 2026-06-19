<?php

declare(strict_types=1);

namespace App\Modules\Theme\Contracts;

interface ThemeServiceInterface
{
    /**
     * Get list of all available themes in the themes/ directory.
     *
     * @return array Array of themes metadata (name, version, author, directory)
     */
    public function getAvailableThemes(): array;

    /**
     * Get the folder name of the currently active theme.
     *
     * @return string
     */
    public function getActiveTheme(): string;

    /**
     * Set the active theme.
     *
     * @param string $themeName Name of the theme directory
     * @return void
     */
    public function setActiveTheme(string $themeName): void;

    /**
     * Get active homepage sections in their display order.
     *
     * @return array Array of active sections (e.g. ['hero', 'news'])
     */
    public function getHomepageSections(): array;

    /**
     * Update active homepage sections and their order.
     *
     * @param array $sections Array of section names
     * @return void
     */
    public function setHomepageSections(array $sections): void;

    /**
     * Get configuration settings for a specific theme.
     *
     * @param string $themeName
     * @return array Key-value pairs of settings
     */
    public function getThemeSettings(string $themeName): array;

    /**
     * Update configuration settings for a specific theme.
     *
     * @param string $themeName
     * @param array $settings Key-value pairs of settings to update
     * @return void
     */
    public function updateThemeSettings(string $themeName, array $settings): void;
}
