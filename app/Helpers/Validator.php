<?php

namespace App\Helpers;

/**
 * Validação simples de dados de formulário, sem dependências externas.
 * Uso: Validator::validate($_POST, ['email' => 'required|email', ...]);
 */
final class Validator
{
    /**
     * @return array<string,string> Lista de erros por campo (vazio = válido)
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;

            foreach (explode('|', $ruleString) as $rule) {
                if (!self::passes($rule, $value)) {
                    $errors[$field] = self::message($field, $rule);
                    break;
                }
            }
        }

        return $errors;
    }

    private static function passes(string $rule, mixed $value): bool
    {
        return match (true) {
            $rule === 'required' => $value !== null && $value !== '',
            $rule === 'email'    => $value === null || $value === '' || filter_var($value, FILTER_VALIDATE_EMAIL) !== false,
            $rule === 'numeric'  => $value === null || $value === '' || is_numeric($value),
            str_starts_with($rule, 'min:') => mb_strlen((string) $value) >= (int) substr($rule, 4),
            str_starts_with($rule, 'max:') => mb_strlen((string) $value) <= (int) substr($rule, 4),
            default => true,
        };
    }

    private static function message(string $field, string $rule): string
    {
        return "Campo '{$field}' inválido ({$rule}).";
    }
}
