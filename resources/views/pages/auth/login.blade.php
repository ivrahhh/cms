<x-layouts.guest title="Login">
    <div class="flex flex-col shadow rounded-lg ring-1 ring-black ring-opacity-5 p-4 w-96">
        <form method="POST" action="{{ route('authenticate') }}" class="space-y-4">
            <div>
                <x-form-label for="email" :value="__('Email Address')" />
                <x-text-box type="email" id="email" name="email" placeholder="example@email.com" :value="old('email')" autocomplete="email" />
                <x-form-error for="email" />
            </div>

            <div>
                <x-form-label for="password" :value="__('Password')" />
                <x-text-box type="password" id="password" name="password" autocomplete="current-password" />
                <x-form-error for="password" />
            </div>
            <div class="flex justify-between items-center px-2">
                <div class="flex items-center gap-1">
                    <input type="checkbox" id="remember" name="remember" value="true" class="peer accent-slate-900"/>
                    <label for="remember" class="text-sm font-semibold block text-gray-700 peer-checked:font-bold">Remember Me</label>
                </div>
                <a href="{{ route('password.request') }}" class="text-sm font-semibold text-blue-600">Forgot Password</a>
            </div>

            <x-form-submit :label="__('Log in')" />
        </form>
    </div>
</x-layouts.guest>