<x-guest-layout>

        <div class="min-h-screen text-white"  style="background-image:url({{asset('media/bg.png')}}); background-size:cover">

            <div class="grid h-full md:h-screen place-items-center md:place-items-start align-center">

                <div class="flex h-full flex-col w-96 p-5 bg-slate-800 md:rounded-none rounded-lg ">
                    <img class="pb-5" src="{{asset('media/logo.png')}}">
                    <div class="text-center">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-label for="password" :value="__('Password')" />

                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-input id="password_confirmation" class="block mt-1 w-full"
                                                type="password"
                                                name="password_confirmation" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="w-full rounded-full m-2 p-4 border-2 text-lg text-gray-200 hover:bg-slate-900 transition ease-linear duration-400  hover:scale-105">
                                {{ __('Reset Password') }}
                            </x-button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
</x-guest-layout>
