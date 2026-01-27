<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MinimumImages implements ValidationRule
{
    protected $minimum;

    public function __construct(int $minimum = 3)
    {
        $this->minimum = $minimum;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            $fail("Field {$attribute} harus berupa array gambar.");
            return;
        }

        if (count($value) < $this->minimum) {
            $fail("Minimal {$this->minimum} gambar diperlukan untuk lelang agunan.");
            return;
        }

        // Validasi setiap file adalah gambar yang valid
        foreach ($value as $index => $file) {
            if (!$file || !$file->isValid()) {
                $fail("Gambar ke-" . ($index + 1) . " tidak valid.");
                return;
            }

            if (!in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp'])) {
                $fail("Gambar ke-" . ($index + 1) . " harus berformat JPG, JPEG, PNG, atau WebP.");
                return;
            }

            if ($file->getSize() > 5120 * 1024) { // 5MB in bytes
                $fail("Gambar ke-" . ($index + 1) . " maksimal berukuran 5MB.");
                return;
            }
        }
    }
}
