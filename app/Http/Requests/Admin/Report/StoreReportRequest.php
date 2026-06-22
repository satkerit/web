<?php

namespace App\Http\Requests\Admin\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware/policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|in:keuangan_publikasi,tata_kelola,tahunan,tahunan_berkelanjutan',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'quarter' => 'nullable|integer|min:1|max:4',
            'file' => 'required|file|mimes:pdf|mimetypes:application/pdf|max:51200',
            'description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'posting_mode' => 'required|in:auto,manual',
            'scheduled_at' => 'nullable|date',
            'published_date' => 'required|date',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->sometimes('scheduled_at', 'required|date', function ($input) {
            return $input->posting_mode === 'manual';
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul laporan wajib diisi.',
            'title.max' => 'Judul laporan maksimal 255 karakter.',
            'type.required' => 'Tipe laporan wajib dipilih.',
            'type.in' => 'Tipe laporan yang dipilih tidak valid.',
            'year.required' => 'Tahun wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.min' => 'Tahun minimal 2000.',
            'year.max' => 'Tahun maksimal ' . (date('Y') + 1) . '.',
            'quarter.integer' => 'Kuartal harus berupa angka.',
            'quarter.min' => 'Kuartal minimal 1.',
            'quarter.max' => 'Kuartal maksimal 4.',
            'file.required' => 'File laporan wajib diunggah.',
            'file.file' => 'File laporan harus berupa file.',
            'file.mimes' => 'File laporan harus berformat PDF.',
            'file.mimetypes' => 'File laporan harus berformat PDF (validasi MIME).',
            'file.max' => 'Ukuran file laporan maksimal 50MB.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
            'is_published.boolean' => 'Status publikasi harus berupa boolean.',
            'posting_mode.required' => 'Mode posting wajib dipilih.',
            'posting_mode.in' => 'Mode posting yang dipilih tidak valid.',
            'scheduled_at.required' => 'Jadwal tayang wajib diisi ketika mode posting adalah "Jadwalkan".',
            'scheduled_at.date' => 'Jadwal tayang harus berupa tanggal yang valid.',
            'published_date.required' => 'Tanggal publish wajib diisi.',
            'published_date.date' => 'Tanggal publish harus berupa tanggal yang valid.',
        ];
    }
}
