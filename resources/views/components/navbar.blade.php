<nav>
    <div x-data="{ account: false }">
        <div class="hidden md:grid h-17 bg-slate-800  grid-flow-col break-keep min-w-screen">
            <div class="flex flex-shrink items-end ">
                <a class="self-center {{ 'countDown' == request()->path() ? 'navButtonActive navButton' : 'navButton' }}  rounded-t-lg pb-4 pt-2"
                    href="{{ route('countDown.index') }}">
                    <img class="mx-5 w-64" src="{{ asset('media/logo.png') }}">
                </a>
                <a href="{{ route('watchlist.index') }}"
                    class="{{ 'watchlist' == request()->path() ? 'navButtonActive navButton' : 'navButton' }}">Watchlist</a>
                <a href="{{ route('shows.index') }}"
                    class="{{ 'shows' == request()->path() ? 'navButtonActive navButton' : 'navButton' }}">Shows</a>
                <a href="{{ route('follows.index') }}"
                    class="{{ 'follows' == request()->path() ? 'navButtonActive navButton' : 'navButton' }}">Follows</a>
                <a href="{{ route('ratings.index') }}"
                    class="{{ 'ratings' == request()->path() ? 'navButtonActive navButton' : 'navButton' }}">Ratings</a>
            </div>



            <div class="flex flex-grow items-end flex-row-reverse">

                <p @mouseover="account = true" @mouseleave="account = false" class="text-base navButton px-0 mr-1"
                    style="cursor: pointer;">{{ Auth::User()->username }}<span
                        class="text-xs hidden xl:inline">({{ Auth::User()->email }})</span>
                <p>

            </div>


        </div>



        <!-- Mobile menu, show/hide based on menu state. -->
        <div x-data="{ open: false }" class="w-full min-h-[18] bg-slate-800 text-white md:hidden">

            <div class="grid grid-flow-col items-center">

                <div class="absolute justify-self-center">
                    <a href="{{ route('countDown.index') }}">
                        <img class="w-56" src="{{ asset('media/logo.png') }}"">
                    </a>
                </div>
                <div class=" justify-self-end flex items-end">
                    <button class="navButton rounded-none" x-on:click="open = ! open"><i class="fa-solid fa-bars fa-lg"
                            style="color: #fcfcfc;"></i></button>
                </div>
            </div>

            <div x-show="open" class="w-full bg-slate-800">

                <div class=" rounded-none pt-5">
                    <p class=" navButtonNoHover text-center">Logged in | {{ Auth::User()->username }}<span
                            class="text-sm "> ({{ Auth::User()->email }})</span>
                    </p>

                    <div class="grid grid-flow-col ">

                        <a style="cursor: pointer;" class=" navButton rounded-none bg-slate-900  mb-0"
                            href="{{ route('follows.show', Auth::id()) }}">
                            <p class="text-sm text-center">Profile</p>
                        </a>


                        @if (strcmp(Auth::user()->role, 'Admin') == 0)
                        <a style="cursor: pointer;" class=" navButton rounded-none bg-slate-900  mb-0"
                            href="{{ route('admin.index') }}">
                            <p class="text-sm text-center">Admin Options</p>
                        </a>
                        @endif

                        <a style="cursor: pointer;" class=" navButton rounded-none bg-slate-900  mb-0"
                            href="{{ route('report.create') }}">
                            <p class="text-sm text-center">Report and message</p>
                        </a>

                        <form onclick='this.submit();return false;' style="cursor: pointer;"
                            class="navButton rounded-none bg-slate-900 mb-0" method="POST"
                            action="{{ route('logout') }}">
                            @csrf
                            <p class=" text-sm text-center">Log out</p>
                        </form>

                    </div>

                </div>
                <hr class="border-2 border-emerald-600">

                <a href="{{ route('countDown.index') }}"
                    class="{{ 'countdown' == request()->path() ? 'navButtonActive navButton' : 'navButton' }} block rounded-none">CountDown</a>
                <a href="{{ route('watchlist.index') }}"
                    class="{{ 'watchlist' == request()->path() ? 'navButtonActive navButton' : 'navButton' }} block rounded-none">Watchlist</a>
                <a href="{{ route('shows.index') }}"
                    class="{{ 'shows' == request()->path() ? 'navButtonActive navButton' : 'navButton' }} block rounded-none">Shows</a>
                <a href="{{ route('follows.index') }}"
                    class="{{ 'follows' == request()->path() ? 'navButtonActive navButton' : 'navButton' }} block rounded-none">Follows</a>
                <a href="{{ route('ratings.index') }}"
                    class="{{ 'ratings' == request()->path() ? 'navButtonActive navButton' : 'navButton' }} block rounded-none">Ratings</a>

                <hr class="border-1 border-emerald-600">


            </div>

        </div>

        <div x-show="account" @mouseover="account = true" @mouseleave="account = false"
            class="z-50 hidden md:inline navButtonNoHover absolute right-1 bg-gray-800 p-0 m-0">
            <div class="rounded-none grid grid-flow-row place-items-center">

                <a style="cursor: pointer;" class="navButton rounded-none w-48 px-0 pl-1 mb-0 hover:bg-gray-700"
                    href="{{ route('follows.show', Auth::id()) }}">
                    <p class="text-sm"><i class="fa-solid fa-user" style="color: #34d399;"></i> Profile</p>
                </a>

                @if (strcmp(Auth::user()->role, 'Admin') == 0)
                    <a style="cursor: pointer;" class="navButton rounded-none w-48 px-0 pl-1 mb-0 hover:bg-gray-700"
                        href="{{ route('admin.index') }}">
                        <p class="text-sm"><i class="fa-solid fa-crown" style="color: #34d399;"></i> Admin Options</p>
                    </a>
                @endif

                <a style="cursor: pointer;" class="navButton rounded-none w-48 px-0 pl-1 mb-0 hover:bg-gray-700"
                    href="{{ route('report.create') }}">
                    <p class="text-sm"><i class="fa-solid fa-bug" style="color: #34d399;"></i> Report and message</p>
                </a>

                <form onclick='this.submit();return false;' style="cursor: pointer;"
                    class="navButton rounded-none w-48 px-0 pl-1 mb-0 hover:bg-gray-700" method="POST"
                    action="{{ route('logout') }}">
                    @csrf
                    <p class="text-sm"><i class="fa-solid fa-right-from-bracket" style="color: #34d399;"></i> Log out
                    </p>
                </form>
            </div>
        </div>


    </div>



</nav>
<br>
