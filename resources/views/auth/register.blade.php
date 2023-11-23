<x-guest-layout>

        <div class="min-h-screen text-white "  style="background-image: url({{asset('media/bg.png')}}); background-size:cover">

            <div class="grid h-full md:h-screen place-items-center md:place-items-start align-center">

                <div class="flex h-full flex-col w-96 p-5 bg-slate-800 md:rounded-none rounded-lg ">
                <img class="pb-5" src="{{asset('media/logo.png')}}">
                <div class="text-center">


        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="username" :value="__('Username')" />

                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="w-36 rounded-full m-2 p-4 border-2 text-lg text-gray-200 hover:bg-slate-900 transition ease-linear duration-400  hover:scale-105">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
        </div>
        <div class="flex justify-between mt-auto p-5">
            <p class="text-gray-600">This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
            <img class="m-5 w-20" src="{{asset('media/TMDB_logo.svg')}}"/>
        </div>
</div>
</div>
</div>

</x-guest-layout>
