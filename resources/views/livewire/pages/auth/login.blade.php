<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="fixed inset-0 min-h-screen w-screen flex flex-col bg-slate-50/70 overflow-y-auto px-4 py-8 sm:px-6 lg:px-8 z-50">
    <div class="w-full max-w-md mx-auto my-auto">
        
        <div class="bg-white border border-slate-100 rounded-2xl shadow-[0_24px_48px_-15px_rgba(15,118,110,0.06)] overflow-hidden">
            
            <div class="bg-gradient-to-r from-teal-800 via-teal-900 to-cyan-950 px-8 py-4.5 text-left relative shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-white tracking-wider">RAPP LEAD-UP</h2>
                        <p class="text-[11px] text-teal-200/60 font-medium tracking-wide">Sign in to manage your programme applications</p>
                    </div>
                </div>
            </div>

            <x-auth-session-status class="m-6 mb-0 p-3 bg-emerald-50/60 text-emerald-800 rounded-xl border border-emerald-100/50 text-xs text-center font-medium" :status="session('status')" />

            <form wire:submit="login" class="p-8 space-y-5">
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5" />
                    <div class="relative group">
                        <x-text-input 
                            wire:model="form.email" 
                            id="email" 
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-sm text-slate-800 placeholder-slate-300 focus:bg-white focus:border-teal-500/40 focus:ring-4 focus:ring-teal-500/5 focus:outline-none transition-all duration-150" 
                            type="email" 
                            name="email" 
                            required 
                            autofocus 
                            autocomplete="username" 
                            placeholder="parent@domain.com"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1.5 text-xs text-rose-600 font-medium tracking-wide" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-slate-400 block" />
                        @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors duration-150 focus:outline-none" href="{{ route('password.request') }}" wire:navigate>
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <x-text-input 
                            wire:model="form.password" 
                            id="password" 
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-sm text-slate-800 placeholder-slate-300 focus:bg-white focus:border-teal-500/40 focus:ring-4 focus:ring-teal-500/5 focus:outline-none transition-all duration-150"
                            type="password"
                            name="password"
                            required 
                            autocomplete="current-password" 
                            placeholder="••••••••"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1.5 text-xs text-rose-600 font-medium tracking-wide" />
                </div>

                <div class="flex items-center pt-1">
                    <label for="remember" class="inline-flex items-center cursor-pointer select-none group">
                        <input 
                            wire:model="form.remember" 
                            id="remember" 
                            type="checkbox" 
                            class="h-4 w-4 rounded border-slate-200 text-teal-600 focus:ring-teal-500/20 focus:ring-offset-0 bg-transparent transition cursor-pointer" 
                            name="remember"
                        >
                        <span class="ms-2.5 text-xs font-semibold text-slate-400 group-hover:text-slate-600 transition-colors duration-150">
                            {{ __('Remember this device') }}
                        </span>
                    </label>
                </div>

                <div class="pt-5 border-t border-slate-50 flex items-center justify-between gap-4 mt-6">
                    <a class="text-xs font-bold text-slate-400 hover:text-teal-700 transition-colors duration-150" href="{{ route('register') }}" wire:navigate>
                        {{ __('Create an account') }}
                    </a>

                    <button type="submit" class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all duration-150 flex items-center justify-center shadow-sm shadow-teal-600/10 active:scale-[0.99] text-xs tracking-wider uppercase focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:ring-offset-2">
                        <span wire:loading.remove wire:target="login">{{ __('Log In') }}</span>
                        <span wire:loading wire:target="login" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Checking...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>