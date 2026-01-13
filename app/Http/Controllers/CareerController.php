<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = Career::available()->orderBy('order_position')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('department', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $careers = $query->paginate(12)->withQueryString();

        // Get unique departments for filter
        $departments = Career::available()
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        return view('frontend.pages.careers.index', compact('careers', 'departments'));
    }

    public function show(Career $career)
    {
        if (!$career->is_active) {
            abort(404);
        }

        $relatedCareers = Career::available()
            ->where('id', '!=', $career->id)
            ->where(function ($q) use ($career) {
                $q->where('department', $career->department)
                    ->orWhere('employment_type', $career->employment_type);
            })
            ->limit(3)
            ->get();

        return view('frontend.pages.careers.show', compact('career', 'relatedCareers'));
    }
}
