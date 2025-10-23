<?php

use App\Services\NumberConverter;

if (!function_exists('numberToWords')) {
    /**
     * Función Helper global para convertir números a palabras.
     */
    function numberToWords(int $number, string $locale ): string
    {
        // Determina el locale: usa el provisto o el actual de la aplicación
        $locale = $locale ?? app()->getLocale();

        // Resuelve y llama al servicio
        $converter = app(NumberConverter::class);

        return $converter->toWords($number, $locale);
    }
}