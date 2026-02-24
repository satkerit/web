<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brochure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrochureController extends Controller
{
    public function index()
    {
        $brochures = Brochure::with('uploader')->latest()->paginate(10);
        return view('admin.brochures.index', compact('brochures'));
    }

    public function create()
    {
        return view('admin.brochures.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // 10MB
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filename = Str::random(40) . '.pdf';
        $path = $file->storeAs('uploads/brosur-syariah', $filename, 'public');

        Brochure::create([
            'filename' => $filename,
            'original_name' => $originalName,
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Brochure uploaded successfully']);
        }

        return redirect()->route('admin.brochures.index')->with('success', 'Brosur berhasil diunggah.');
    }

    public function destroy(Brochure $brochure)
    {
        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($brochure->file_path)) {
                Storage::disk('public')->delete($brochure->file_path);
            }

            // Delete record from database
            $brochure->delete();

            return redirect()->route('admin.brochures.index')
                ->with('success', 'Brosur berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.brochures.index')
                ->with('error', 'Gagal menghapus brosur: ' . $e->getMessage());
        }
    }

    public function download(Brochure $brochure)
    {
        if (!Storage::disk('public')->exists($brochure->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($brochure->file_path, $brochure->original_name);
    }
}