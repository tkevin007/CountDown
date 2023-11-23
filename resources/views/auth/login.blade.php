<x-guest-layout>



        <div class="  text-white "  style=" overflow: auto ;background-size: 100% 100vh; background-image: url({{asset('media/bg.png')}}); background-size:cover; background-repeat:repeat-y">

            <div class="grid h-full md:h-screen place-items-center md:place-items-start align-center">

                <div class="flex h-full flex-col w-96 p-5 bg-slate-800 md:rounded-none rounded-lg ">
                    <img class="mb-10 w-72 self-center" src="{{asset('media/logo_xl.png')}}">
                    <div class="text-center">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-label for="password" :value="__('Password')" />

                            <x-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="current-password" />
                        </div>

                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <!-- Forgot Password-->
                        <div class="flex items-center justify-between mt-4">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-300 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                            <div>
                            <x-button class="w-36 rounded-full m-2 p-4 border-2 text-lg text-gray-200 hover:bg-slate-900 transition ease-linear duration-400  hover:scale-105">
                                {{ __('Log in') }}
                            </x-button>
                            </div>
                        </div>
                    </form>


                    <div class="grid mt-7">
                        <div class="flex items-center py-4">
                            <!-- The left line -->
                            <div class="flex-grow h-px bg-gray-400"></div>

                            <!-- Your text here -->
                            <span class="flex-shrink text-2xl text-gray-500 px-4 italic font-light">Need an account?</span>

                            <!-- The right line -->
                            <div class="flex-grow h-px bg-gray-400"></div>
                        </div>
                        <a href="{{ route('register') }}" class="w-full rounded-full p-4 border-2 text-lg text-gray-200 hover:bg-slate-900 transition ease-linear duration-400 hover:scale-105">Register</a>
                    </div>

                    </div>

                    <div class="flex justify-between mt-auto p-5">
                        <p class="text-gray-600">This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
                        <img class="m-5 w-20" src="{{asset('media/TMDB_logo.svg')}}"/>
                    </div>
            </div>
        </div>
    </div>

</x-guest-layout>
