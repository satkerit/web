<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    protected int $minLength = 12;
    protected bool $requireUppercase = true;
    protected bool $requireLowercase = true;
    protected bool $requireNumbers = true;
    protected bool $requireSpecialChars = true;
    protected array $commonPasswords = [
        'password', 'password123', '12345678', 'qwerty123', 'admin123',
        'welcome123', 'letmein', 'monkey', '1234567890', 'password1',
    ];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('Password harus berupa string.');
            return;
        }

        // Check minimum length
        if (strlen($value) < $this->minLength) {
            $fail("Password minimal {$this->minLength} karakter.");
            return;
        }

        // Check for uppercase letters
        if ($this->requireUppercase && !preg_match('/[A-Z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf besar.');
            return;
        }

        // Check for lowercase letters
        if ($this->requireLowercase && !preg_match('/[a-z]/', $value)) {
            $fail('Password harus mengandung minimal 1 huruf kecil.');
            return;
        }

        // Check for numbers
        if ($this->requireNumbers && !preg_match('/[0-9]/', $value)) {
            $fail('Password harus mengandung minimal 1 angka.');
            return;
        }

        // Check for special characters
        if ($this->requireSpecialChars && !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value)) {
            $fail('Password harus mengandung minimal 1 karakter spesial (!@#$%^&*...).');
            return;
        }

        // Check for common passwords
        if (in_array(strtolower($value), $this->commonPasswords)) {
            $fail('Password terlalu umum. Gunakan password yang lebih unik.');
            return;
        }

        // Check for sequential characters
        if ($this->hasSequentialChars($value)) {
            $fail('Password tidak boleh mengandung karakter berurutan (123, abc, dll).');
            return;
        }

        // Check for repeated characters
        if ($this->hasRepeatedChars($value)) {
            $fail('Password tidak boleh mengandung karakter yang berulang lebih dari 3 kali.');
            return;
        }
    }

    /**
     * Check for sequential characters
     */
    protected function hasSequentialChars(string $password): bool
    {
        $sequences = [
            '0123456789',
            'abcdefghijklmnopqrstuvwxyz',
            'qwertyuiop',
            'asdfghjkl',
            'zxcvbnm',
        ];

        foreach ($sequences as $sequence) {
            for ($i = 0; $i < strlen($sequence) - 3; $i++) {
                $seq = substr($sequence, $i, 4);
                if (stripos($password, $seq) !== false) {
                    return true;
                }
                // Check reverse
                if (stripos($password, strrev($seq)) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check for repeated characters
     */
    protected function hasRepeatedChars(string $password): bool
    {
        return preg_match('/(.)\1{3,}/', $password);
    }

    /**
     * Set minimum length
     */
    public function minLength(int $length): self
    {
        $this->minLength = $length;
        return $this;
    }

    /**
     * Disable uppercase requirement
     */
    public function withoutUppercase(): self
    {
        $this->requireUppercase = false;
        return $this;
    }

    /**
     * Disable lowercase requirement
     */
    public function withoutLowercase(): self
    {
        $this->requireLowercase = false;
        return $this;
    }

    /**
     * Disable numbers requirement
     */
    public function withoutNumbers(): self
    {
        $this->requireNumbers = false;
        return $this;
    }

    /**
     * Disable special characters requirement
     */
    public function withoutSpecialChars(): self
    {
        $this->requireSpecialChars = false;
        return $this;
    }
}
