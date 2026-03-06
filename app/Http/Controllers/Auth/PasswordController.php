<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordHistory;
use App\Rules\StrongPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', new StrongPassword(), 'confirmed'],
        ]);

        // Check if password was recently used
        if ($request->user()->hasUsedPassword($validated['password'])) {
            throw ValidationException::withMessages([
                'password' => 'Password ini sudah pernah digunakan. Silakan gunakan password yang berbeda.',
            ])->errorBag('updatePassword');
        }

        // Save current password to history before updating
        PasswordHistory::savePassword($request->user()->id, $validated['password']);

        // Update password
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log password change
        \Log::info('Password changed', [
            'user_id' => $request->user()->id,
            'ip' => $request->ip(),
        ]);

        return back()->with('status', 'password-updated');
    }
}
