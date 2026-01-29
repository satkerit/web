<?php

namespace App\Http\Controllers;

use App\Models\Brochure;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrochureController extends Controller
{
    public function index()
    {
        $brochures = Brochure::latest()->get();
        return view('frontend.pages.brochures.index', compact('brochures'));
    }

    public function download(Brochure $brochure)
    {
        // Log activity
        AuditTrail::log(
            'download',
            'Mengunduh brosur: ' . $brochure->original_name,
            $brochure
        );

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

        return Storage::disk('public')->response($brochure->file_path, $brochure->original_name, [
            'Content-Disposition' => 'inline; filename="' . $brochure->original_name . '"',
            'Content-Type' => 'application/pdf',
        ]);
    }
}
