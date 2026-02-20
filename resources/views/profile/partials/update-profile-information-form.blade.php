<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    {{-- Email verification --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Update profile --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- NAME --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button
                            form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400
                                   hover:text-gray-900 dark:hover:text-gray-100
                                   rounded-md focus:outline-none focus:ring-2
                                   focus:ring-offset-2 focus:ring-indigo-500
                                   dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- 🎓 FORMATION (UNIQUEMENT POUR ÉTUDIANT) --}}
        @if($user->role === 'student')
            <div>
                <x-input-label for="formation" :value="__('Formation')" />
<select
    id="formation"
    name="formation"
    class="mt-1 block w-full border-gray-300 dark:border-gray-700
           dark:bg-gray-900 dark:text-gray-300
           focus:border-indigo-500 focus:ring-indigo-500
           rounded-md shadow-sm"
>
    <option value="">-- Sélectionner une formation --</option>

    <option value="BTS SIO SLAM"
        {{ old('formation', $user->formation) === 'BTS SIO SLAM' ? 'selected' : '' }}>
        BTS SIO - SLAM
    </option>

    <option value="BTS SIO SISR"
        {{ old('formation', $user->formation) === 'BTS SIO SISR' ? 'selected' : '' }}>
        BTS SIO - SISR
    </option>

    <option value="Bachelor DevOps"
        {{ old('formation', $user->formation) === 'Bachelor DevOps' ? 'selected' : '' }}>
        Bachelor DevOps
    </option>

    <option value="Bachelor Cybersécurité"
        {{ old('formation', $user->formation) === 'Bachelor Cybersécurité' ? 'selected' : '' }}>
        Bachelor Cybersécurité
    </option>

    <option value="Mastère IT"
        {{ old('formation', $user->formation) === 'Mastère IT' ? 'selected' : '' }}>
        Mastère IT
    </option>

    <option value="Mastère Réseau"
        {{ old('formation', $user->formation) === 'Mastère Réseau' ? 'selected' : '' }}>
        Mastère Réseau
    </option>

</select>


                <x-input-error class="mt-2" :messages="$errors->get('formation')" />
            </div>
        @endif

        {{-- SAVE BUTTON --}}
        <div class="flex items-center gap-4">
            <x-primary-button>
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
