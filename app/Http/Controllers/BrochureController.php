<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brochure;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrochureController extends Controller
{
    public function index()
    {
        // Ambil produk pembiayaan yang memiliki brosur dan aktif
        $pembiayaanProducts = Product::with('libraryBrochure')
            ->where('type', 'pembiayaan_syariah')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNotNull('brochure')
                    ->orWhereNotNull('brochure_id');
            })
            ->orderBy('order_position')
            ->orderBy('name')
            ->get();

        // Ambil juga brosur dari tabel brosur (untuk kompatibilitas)
        $brochures = Brochure::with('uploader')
            ->latest()
            ->get();

        return view('frontend.pages.brochures.index', compact('pembiayaanProducts', 'brochures'));
    }

    public function download(Brochure $brochure)
    {
        // Log activity
        AuditTrail::log(
            'download',
            'Mengunduh brosur: ' . $brochure->original_name,
            $brochure
        );

        if (!Storage::disk('public')->exists($brochure->file_path)) {
            abort(404, 'File brosur tidak ditemukan.');
        }

        return Storage::disk('public')->download($brochure->file_path, $brochure->original_name);
    }

    public function preview(Brochure $brochure)
    {
        // Log activity
        AuditTrail::log(
            'view',
            'Melihat preview brosur: ' . $brochure->original_name,
            $brochure
        );

        if (!Storage::disk('public')->exists($brochure->file_path)) {
            abort(404, 'File brosur tidak ditemukan.');
        }

        return Storage::disk('public')->response($brochure->file_path, $brochure->original_name, [
            'Content-Disposition' => 'inline; filename="' . $brochure->original_name . '"',
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadProduct(Product $product)
    {
        $product->loadMissing('libraryBrochure');

        $brochurePath = $product->getEffectiveBrochurePath();

        if (!$brochurePath || !Storage::disk('public')->exists($brochurePath)) {
            abort(404, 'File brosur produk tidak ditemukan.');
        }

        // Log activity
        AuditTrail::log(
            'download',
            'Mengunduh brosur produk: ' . $product->name,
            $product
        );

        $filename = $product->getEffectiveBrochureName() ?? (str_replace(' ', '_', $product->name) . '_Brosur.pdf');

        return Storage::disk('public')->download($brochurePath, $filename);
    }

    public function previewProduct(Product $product)
    {
        $product->loadMissing('libraryBrochure');

        $brochurePath = $product->getEffectiveBrochurePath();

        if (!$brochurePath || !Storage::disk('public')->exists($brochurePath)) {
            abort(404, 'File brosur produk tidak ditemukan.');
        }

        // Log activity
        AuditTrail::log(
            'view',
            'Melihat preview brosur produk: ' . $product->name,
            $product
        );

        $filename = $product->getEffectiveBrochureName() ?? (str_replace(' ', '_', $product->name) . '_Brosur.pdf');

        return Storage::disk('public')->response($brochurePath, $filename, [
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Content-Type' => 'application/pdf',
        ]);
    }
}
