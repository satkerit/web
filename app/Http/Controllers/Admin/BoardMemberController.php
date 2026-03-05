<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Traits\AuthorizesAdminActions;
use App\Traits\HandlesImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoardMemberController extends Controller
{
    use HandlesImageUpload, AuthorizesAdminActions;

    public function index(Request $request)
    {
        $this->authorizeView('board.view');

        $query = BoardMember::orderBy('type')->orderBy('order_position');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $members = $query->paginate(15)->withQueryString();

        return view('admin.board-members.index', compact('members'));
    }

    public function create()
    {
        $this->authorizeCreate('board.manage');

        return view('admin.board-members.form');
    }

    public function store(Request $request)
    {
        $this->authorizeCreate('board.manage');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'type' => 'required|in:komisaris,direksi,pengawas_syariah',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'biography' => 'nullable|string',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'order_position' => 'nullable|integer|min:0',
        ]);

        // Filter empty values from arrays
        if (isset($validated['education'])) {
            $validated['education'] = array_values(array_filter($validated['education'], fn($v) => !empty(trim($v))));
        }
        if (isset($validated['experience'])) {
            $validated['experience'] = array_values(array_filter($validated['experience'], fn($v) => !empty(trim($v))));
        }

        $validated['photo'] = $this->handleImageUpload($request, 'photo', 'board-members');

        BoardMember::create($validated);

        return redirect()->route('admin.board-members.index')->with('success', 'Anggota dewan berhasil ditambahkan.');
    }

    public function edit(BoardMember $boardMember)
    {
        $this->authorizeEdit('board.manage');

        return view('admin.board-members.form', compact('boardMember'));
    }

    public function update(Request $request, BoardMember $boardMember)
    {
        $this->authorizeEdit('board.manage');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'type' => 'required|in:komisaris,direksi,pengawas_syariah',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'biography' => 'nullable|string',
            'education' => 'nullable|array',
            'experience' => 'nullable|array',
            'order_position' => 'nullable|integer|min:0',
        ]);

        // Filter empty values from arrays
        if (isset($validated['education'])) {
            $validated['education'] = array_values(array_filter($validated['education'], fn($v) => !empty(trim($v))));
        }
        if (isset($validated['experience'])) {
            $validated['experience'] = array_values(array_filter($validated['experience'], fn($v) => !empty(trim($v))));
        }

        $validated['photo'] = $this->handleImageUpload($request, 'photo', 'board-members', $boardMember->photo);

        $boardMember->update($validated);

        return redirect()->route('admin.board-members.index')->with('success', 'Anggota dewan berhasil diperbarui.');
    }

    public function destroy(BoardMember $boardMember)
    {
        $this->authorizeDelete('board.manage');

        if ($boardMember->photo) {
            Storage::disk('public')->delete($boardMember->photo);
        }

        $boardMember->delete();

        return redirect()->route('admin.board-members.index')->with('success', 'Anggota dewan berhasil dihapus.');
    }
}
