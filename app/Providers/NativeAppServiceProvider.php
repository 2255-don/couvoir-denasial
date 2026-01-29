<?php

namespace App\Providers;

use Native\Desktop\Facades\Window;
use Native\Desktop\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
        ->width(1280)         // Largeur par défaut
        ->height(800)        // Hauteur par défaut
        ->minWidth(800)      // Taille minimale
        ->minHeight(600)
        ->showDevTools(false) // <--- ICI : ça cache l'inspecteur (F12)
        ->rememberState();
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
