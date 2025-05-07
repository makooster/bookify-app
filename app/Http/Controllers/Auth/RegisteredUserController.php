<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        Log::debug('Validation starting', ['input' => $request->all()]);
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'in:user,owner,admin'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except('password')
            ]);
            return back()->withErrors($e->errors())->withInput();
//            throw $e;
        }

        Log::debug('Request data', [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role
        ]);

        Log::debug('Validation passed', $validated);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'email_verified_at' => null
            ]);

            Log::debug('User created in memory', $user->toArray());

            Log::debug('After User::create', ['id' => $user->id]);

            // Immediate database verification
            $dbUser = DB::table('users')->find($user->id);
            if (!$dbUser) {
                throw new \Exception('User not found in database after creation');
            }

            event(new Registered($user));
            Auth::login($user);

            DB::commit();

            Log::debug('Registration completed successfully');
            return redirect()->route('home');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except('password')
            ]);

            return back()
                ->withInput()
                ->withErrors(['registration' => 'Registration failed. Please try again.']);
        }
    }
}
