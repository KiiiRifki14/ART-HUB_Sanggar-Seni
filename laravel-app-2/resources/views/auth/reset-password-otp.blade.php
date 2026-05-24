<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Masukkan email Anda, kode OTP yang telah dikirim, dan password baru Anda.') }}
    </div>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-100" type="email" name="email" :value="session('reset_email')" required autofocus readonly />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- OTP Code -->
        <div class="mt-4">
            <x-input-label for="otp_code" :value="__('Kode OTP (6 digit)')" />
            <x-text-input id="otp_code" class="block mt-1 w-full text-center tracking-widest text-lg" type="text" name="otp_code" required autofocus autocomplete="one-time-code" maxlength="6" />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password Baru')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
