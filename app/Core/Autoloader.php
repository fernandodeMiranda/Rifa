<?php

/**
 * Autoloader manual (sem Composer). Mapeia o namespace `App\` para o
 * diretório `app/`, seguindo a mesma estrutura de pastas das classes.
 *
 * Ex.: App\Services\RifaService  ->  app/Services/RifaService.php
 */
final class Autoloader
{
    public static function register(string $baseDir): void
    {
        spl_autoload_register(function (string $class) use ($baseDir): void {
            $prefix = 'App\\';

            if (!str_starts_with($class, $prefix)) {
                return;
            }

            $relative = substr($class, strlen($prefix));
            $path = $baseDir . '/' . str_replace('\\', '/', $relative) . '.php';

            if (is_file($path)) {
                require $path;
            }
        });
    }
}
