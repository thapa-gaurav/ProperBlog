<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $errors = [];
        if (strlen($value) < 8) {
            $errors[] = 'be at least 8 characters';
        }
        if (!preg_match('/[A-Z]/', $value)) {
            $errors[] = 'contain at least one uppercase character';
        }
        if (!preg_match('/[a-z]/', $value)) {
            $errors[] = 'contain at least one lowercase character';
        }
        if (!preg_match('/\d/', $value)) {
            $errors[] = 'contain at least one numerical character';
        }
        if (!preg_match('/[^a-zA-Z\d]/', $value)) {
            $errors[] = 'contain at least one special character';
        }
        if (!empty($errors)) {
            $errorMessage = 'The password must ' . implode(', ', $errors);
            $fail($errorMessage);
        }
    }

}
