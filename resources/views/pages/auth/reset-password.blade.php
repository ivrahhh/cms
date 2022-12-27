<x-layouts.guest title="Reset Password">
    <div class="flex flex-col shadow rounded-lg ring-1 ring-black ring-opacity-5 p-4 w-96">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            <div>
                <x-form-label for="email" :value="__('Email Address')" />
                <x-text-box type="email" id="email" name="email" placeholder="example@email.com" :value="old('email', $email)" autocomplete="email" readonly/>
                <x-form-error for="email" />
            </div>

            <div>
                <x-form-label for="password" :value="__('Password')" />
                <x-text-box type="password" id="password" name="password" autocomplete="new-password" />
                <x-form-error for="password" />
            </div>

            <div>
                <x-form-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-box type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" />
            </div>

            @method('PUT')
            <input type="hidden" name="token" value="{{ $token }}" />

            <x-form-submit :label="__('Reset Password')" />
        </form>
    </div>
</x-layouts.guest>