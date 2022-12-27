<div>
    <form wire:submit.prevent="resend">
        <x-form-submit :label="__('Resend Verification Link')" wire:loading.attr="disabled" class="disabled:opacity-75 disabled:hover:bg-slate-900 disabled:hover:border-transparent disabled:hover:text-white" />
    </form>
</div>
