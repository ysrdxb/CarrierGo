<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
	public function update(Request $request): RedirectResponse
	{
		try {
			// Validate the incoming request data
			$validatedData = $request->validate([
				'email' => 'required|email',
				'password' => 'required|string|min:8|confirmed',
			]);

			// Attempt to find the user by email
			$user = User::where('email', $validatedData['email'])->first();

			// Check if the user was found
			if (!$user) {
				return redirect()->back()->withErrors(['email' => 'User not found.']);
			}

			// Update the user's password
			$user->password = Hash::make($validatedData['password']);
			$user->save();

			// Redirect to the login page with a success message
			return redirect('login')->with('status', 'Your password has been updated!');
		} catch (Exception $e) {
			// Log the exception for debugging purposes
			Log::error('Error updating password: ' . $e->getMessage());

			// Redirect back with an error message
			return redirect()->back()->withErrors(['error' => $e->getMessage()]);
		}
	}
}
