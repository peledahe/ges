<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        @if (Auth::user()->isDemo())
            <div class="col-span-6 p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700 text-xs text-amber-800 dark:text-amber-200 flex items-start gap-3">
                <span class="text-xl">🔒</span>
                <div>
                    <p class="font-bold">Esta es una cuenta de demostración</p>
                    <p class="mt-0.5 text-amber-700 dark:text-amber-300">
                        La contraseña está protegida contra modificaciones para garantizar el acceso público al entorno demo del sistema.
                    </p>
                </div>
            </div>
        @else
            <div class="col-span-6 sm:col-span-4">
                <x-label for="current_password" value="{{ __('Current Password') }}" />
                <x-input id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" />
                <x-input-error for="current_password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="password" value="{{ __('New Password') }}" />
                <x-input id="password" type="password" class="mt-1 block w-full" wire:model="state.password" autocomplete="new-password" />
                <x-input-error for="password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="state.password_confirmation" autocomplete="new-password" />
                <x-input-error for="password_confirmation" class="mt-2" />
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        @if (!Auth::user()->isDemo())
            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button>
                {{ __('Save') }}
            </x-button>
        @endif
    </x-slot>
</x-form-section>
