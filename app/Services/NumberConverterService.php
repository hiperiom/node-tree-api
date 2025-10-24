<?php
// app/Services/NumberConverter.php

namespace App\Services;

use NumberFormatter;

class NumberConverterService
{
    /**
     * Convierte un número entero a su representación textual en un idioma dado.
     *
     * @param int $number
     * @param string $locale (e.g., 'en', 'es', 'fr')
     * @return string|false
     */
    public function toWords(int $number, string $locale = 'en')
    {
        // Verifica si la extensión intl está disponible
        if (!extension_loaded('intl')) {
            // En un entorno de producción, puedes loguear un error
            // o usar un fallback a una lógica simple para números pequeños.
            return (string) $number; 
        }

        try {
            // NumberFormatter::SPELLOUT es la constante para convertir a palabras
            $formatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
            return $formatter->format($number);
        } catch (\Exception $e) {
            // Manejo de errores si el locale no es compatible o el número es muy grande
            return (string) $number;
        }
    }
}