<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="fixed inset-0 min-h-screen w-screen flex flex-col bg-slate-50/70 overflow-y-auto px-4 py-8 sm:px-6 lg:px-8 z-50">
    <div class="w-full max-w-md mx-auto my-auto">

        <div class="bg-white border border-slate-100 rounded-2xl shadow-[0_24px_48px_-15px_rgba(15,118,110,0.06)] overflow-hidden">

            <!-- Header Banner -->
            <div class="bg-gradient-to-r from-teal-800 via-teal-900 to-cyan-950 px-8 py-4.5 text-left relative shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-black text-white tracking-wider">RAPP LEAD-UP</h2>
                        <p class="text-[11px] text-teal-200/60 font-medium tracking-wide">Request a password reset link</p>
                    </div>
                </div>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="m-6 mb-0 p-3 bg-emerald-50/60 text-emerald-800 rounded-xl border border-emerald-100/50 text-xs text-center font-medium" :status="session('status')" />

            <!-- Form Body -->
            <form wire:submit="sendPasswordResetLink" class="p-8 space-y-5">
                
                <p class="text-xs text-slate-500 leading-relaxed">
                    {{ __('Forgot your password? No problem. Enter your email address and we will send you a reset link to choose a new one.') }}
                </p>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5" />
                    <div class="relative group">
                        <x-text-input
                            wire:model="email"
                            id="email"
                            class="block w-full px-4 py-3 bg-slate-50/50 border border-slate-100 rounded-xl text-sm text-slate-800 placeholder-slate-300 focus:bg-white focus:border-teal-500/40 focus:ring-4 focus:ring-teal-500/5 focus:outline-none transition-all duration-150"
                            type="email"
                            name="email"
                            required
                            autofocus
                            placeholder="parent@domain.com"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-600 font-medium tracking-wide" />
                </div>

                <!-- Actions -->
                <div class="pt-5 border-t border-slate-50 flex items-center justify-between gap-4 mt-6">
                    <a class="text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors duration-150 focus:outline-none" href="{{ route('login') }}" wire:navigate>
                        {{ __('Back to login') }}
                    </a>

                    <button type="submit"
                        class="w-full sm:w-auto bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 px-6 rounded-xl transition-all duration-150 flex items-center justify-center shadow-sm shadow-teal-600/10 active:scale-[0.99] text-xs tracking-wider uppercase focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:ring-offset-2">
                        <span wire:loading.remove wire:target="sendPasswordResetLink">
                            {{ __('Send Link') }}
                        </span>

                        <span wire:loading wire:target="sendPasswordResetLink"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap">
                            <svg class="animate-spin h-3.5 w-3.5 text-white shrink-0" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{ __('Sending...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>