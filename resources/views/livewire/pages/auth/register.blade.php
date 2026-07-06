<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="fixed inset-0 min-h-screen w-screen flex items-center justify-center bg-[#fbfbfd] overflow-y-auto px-4 py-12 z-50">
    <!-- Borderless Ambient Shadow Card Container -->
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-[0_32px_64px_-12px_rgba(0,0,0,0.06)] p-8 sm:p-12 my-auto">
        
        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold tracking-tight text-neutral-900">
                Create account
            </h2>
            <p class="mt-2 text-sm text-neutral-400">
                Get started with your new profile.
            </p>
        </div>

        <form wire:submit="register" class="space-y-5">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-xs font-semibold text-neutral-700 block mb-1.5" />
                <x-text-input 
                    wire:model="name" 
                    id="name" 
                    class="block w-full px-4 py-3 bg-neutral-50 border border-transparent rounded-xl text-sm text-neutral-900 placeholder-neutral-400 focus:bg-white focus:border-neutral-900 focus:ring-0 focus:outline-none transition-all duration-150" 
                    type="text" 
                    name="name" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    placeholder="John Doe"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-rose-600 font-medium" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-xs font-semibold text-neutral-700 block mb-1.5" />
                <x-text-input 
                    wire:model="email" 
                    id="email" 
                    class="block w-full px-4 py-3 bg-neutral-50 border border-transparent rounded-xl text-sm text-neutral-900 placeholder-neutral-400 focus:bg-white focus:border-neutral-900 focus:ring-0 focus:outline-none transition-all duration-150" 
                    type="email" 
                    name="email" 
                    required 
                    autocomplete="username" 
                    placeholder="name@company.com"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-600 font-medium" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-neutral-700 block mb-1.5" />
                <x-text-input 
                    wire:model="password" 
                    id="password" 
                    class="block w-full px-4 py-3 bg-neutral-50 border border-transparent rounded-xl text-sm text-neutral-900 placeholder-neutral-400 focus:bg-white focus:border-neutral-900 focus:ring-0 focus:outline-none transition-all duration-150"
                    type="password"
                    name="password"
                    required 
                    autocomplete="new-password" 
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-600 font-medium" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold text-neutral-700 block mb-1.5" />
                <x-text-input 
                    wire:model="password_confirmation" 
                    id="password_confirmation" 
                    class="block w-full px-4 py-3 bg-neutral-50 border border-transparent rounded-xl text-sm text-neutral-900 placeholder-neutral-400 focus:bg-white focus:border-neutral-900 focus:ring-0 focus:outline-none transition-all duration-150"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-rose-600 font-medium" />
            </div>

            <!-- Options & Action Footer -->
            <div class="flex items-center justify-between pt-2">
                <a class="text-xs font-medium text-neutral-400 hover:text-neutral-900 transition-colors duration-150 focus:outline-none" href="{{ route('login') }}" wire:navigate>
                    {{ __('Already registered?') }}
                </a>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <x-primary-button class="w-full py-3 px-4 flex justify-center items-center bg-neutral-900 hover:bg-neutral-800 active:bg-neutral-950 text-white text-sm font-medium rounded-xl border border-transparent transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-neutral-900 focus:ring-offset-2">
                    <span wire:loading.remove wire:target="register">{{ __('Register') }}</span>
                    <span wire:loading wire:target="register" class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('Creating Profile...') }}
                    </span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>