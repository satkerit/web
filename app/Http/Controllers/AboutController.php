<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Services\CacheService;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function company()
    {
        return view('frontend.pages.about.company', [
            'companyInfo' => CacheService::getCompanyInfo(),
        ]);
    }

    public function komisaris()
    {
        return view('frontend.pages.about.board-members', [
            'members' => CacheService::getBoardMembers('komisaris'),
            'title' => 'Dewan Komisaris',
            'subtitle' => 'Dewan Komisaris BPR Syariah',
        ]);
    }

    public function direksi()
    {
        return view('frontend.pages.about.board-members', [
            'members' => CacheService::getBoardMembers('direksi'),
            'title' => 'Dewan Direksi',
            'subtitle' => 'Dewan Direksi BPR Syariah',
        ]);
    }

    public function pengawasSyariah()
    {
        return view('frontend.pages.about.board-members', [
            'members' => CacheService::getBoardMembers('pengawas_syariah'),
            'title' => 'Dewan Pengawas Syariah',
            'subtitle' => 'Dewan Pengawas Syariah BPR Syariah',
        ]);
    }

    public function struktur()
    {
        return view('frontend.pages.about.struktur');
    }

    public function offices(Request $request)
    {
        $type = $request->query('type');

        return view('frontend.pages.about.offices', [
            'offices' => CacheService::getOffices($type),
        ]);
    }

    public function officeShow(Office $office)
    {
        abort_unless($office->is_active, 404);

        $otherOffices = Office::where('is_active', true)
            ->where('id', '!=', $office->id)
            ->orderBy('type')
            ->limit(5)
            ->get(['id', 'name', 'slug', 'type', 'photo']);

        return view('frontend.pages.about.office-show', compact('office', 'otherOffices'));
    }
}
