<x-layouts.guest title="Email Verification">
    <div class="flex flex-col shadow rounded-lg ring-1 ring-black ring-opacity-5 p-4 w-96 gap-4">
        <p class="text-sm text-gray-700">Thank you for using our website.Please verify your email address to continue using the website. We send the link to your email. Just click the link to verify your account. In case you don't recieve a verification link you can click the button below so that we can send you another one.</p>
        <livewire:resend-verification-link />
    </div>
</x-layouts.guest>