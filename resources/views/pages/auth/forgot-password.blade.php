<x-layouts.guest title="Forgot Password">
    <div class="flex flex-col shadow rounded-lg p-4 w-96 ring-1 ring-black ring-opacity-5">
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            <p class="text-sm text-gray-700">
                {{ __('Forgot your password? No problem you can just submit your email address below so we can send you the reset link. Just make sure that the email is valid email address.') }}
            </p>
            <div>
                <x-form-label for="email" :value="__('Email Address')" />
                <x-text-box type="email" id="email" name="email" placeholder="example@email.com" :value="old('email')" autocomplete="email"/>
                <x-form-error for="email" />
            </div>
            <div class="flex flex-col gap-2">
                <x-form-submit :label="__('Request Password Reset')" />
                <a href="{{ route('login') }}" class="block text-sm font-semibold text-blue-600 text-center">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</x-layouts.guest>