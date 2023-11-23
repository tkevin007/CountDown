<x-guest-layout>

        <div class="min-h-screen text-white "  style="background-image: url({{asset('media/bg.png')}}); background-size:cover">

            <div class="grid h-full md:h-screen place-items-center md:place-items-start align-center">

                <div class="flex h-full flex-col w-96 p-5 bg-slate-800 md:rounded-none rounded-lg ">
                    <img class="pb-5" src="{{asset('media/logo.png')}}">
                    <div class="text-center">



        <div class="mb-4 text-sm text-gray-400">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="w-full rounded-full p-4 border-2 text-lg text-gray-200 hover:bg-slate-900 transition ease-linear duration-400 hover:scale-105">
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
        </div>
    </div>
</div>
</div>

</x-guest-layout>
