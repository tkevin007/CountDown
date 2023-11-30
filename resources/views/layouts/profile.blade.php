<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<!-- A message about the succes of a new follow-->
@if (Session::has('followed'))
    <x-alertbox primaryText="Success! " secondaryText="You started following {{ Session::get('followed') }}">
    </x-alertbox>
@endif

<!-- A message about the success of unfollow-->
@if (Session::has('unfollowed'))
    <x-alertbox primaryText="Success! " secondaryText="You stopped following {{ Session::get('unfollowed') }}">
    </x-alertbox>
@endif

<div class="bg-gray-950 md:w-5/6 w-full m-auto mt-0 border-4 border-black">

    <!-- The header of the profile, with the name and options to follow or unfollow-->
    <h1 class="bg-emerald-400 text-black p-5 mb-1 text-2xl md:mb-2  ">
        @if (strcmp($user->role, 'Admin') == 0)
            <i class="fa-solid fa-crown mr-5 float-left" style="color: #000000;"></i>
        @else
            <i class="fa-solid fa-user mr-5 float-left" style="color: #000000;"></i>
        @endif
        {{ $user->username }}'s Profile
        <div class="hidden md:inline">
            @if ($allowFollow)
                <form action="{{ route('follows.store') }}" method="POST"
                    class="transition ease-in-out  delay-100 hover:scale-110 hover:bg-gray-900 float-right bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                    style="cursor: pointer" onclick="this.submit()">
                    @csrf
                    @method('POST')
                    <input hidden id="user_id" name="user_id" value="{{ $user->id }}">
                    Follow
                </form>
            @elseif($allowUnFollow)
                <form action="{{ route('follows.destroy', $user->id) }}" method="POST"
                    class="transition ease-in-out  delay-100 hover:scale-110 hover:bg-gray-900 float-right bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                    style="cursor: pointer" onclick="this.submit()">
                    @csrf
                    @method('DELETE')
                    Unfollow
                </form>
            @endif
            <div>
    </h1>
    <h1 class=" text-black p-2 mb-2 text-2xl grid justify-items-center content-center md:hidden">
        @if ($allowFollow)
            <form action="{{ route('follows.store') }}" method="POST"
                class="border-emerald-400 border-2 transition ease-in-out  delay-100 hover:scale-110 hover:bg-gray-900 float-right bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                style="cursor: pointer" onclick="this.submit()">
                @csrf
                @method('POST')
                <input hidden id="user_id" name="user_id" value="{{ $user->id }}">
                Follow
            </form>
        @elseif($allowUnFollow)
            <form action="{{ route('follows.destroy', $user->id) }}" method="POST"
                class="border-emerald-400 border-2 transition ease-in-out  delay-100 hover:scale-110 hover:bg-gray-900 float-right bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                style="cursor: pointer" onclick="this.submit()">
                @csrf
                @method('DELETE')
                Unfollow
            </form>
        @endif
    </h1>


    <!-- Some stats about the user-->
    <div class="flex flex-row flex-wrap justify-around text-white mt-2 md:mt-0">
        <span>
            <b class="text-white">{{ $user->shows->count() }}</b> Shows
        </span>
        <span>
            <b class="text-white">{{ $user->ratings->count() }}</b> Ratings
        </span>
        <span>
            <b class="text-white">{{ $user->follows->count() }}</b> Follows
        </span>
        <span>
            <b class="text-white">{{ count($followers) }}</b> Followers
        </span>

        <span><i class="fa-solid fa-calendar-days" style="color: #34d399;"></i> Registered at:
            {{ $user->created_at }}
        </span>
    </div>

    <!-- A list of the user's shows with some details about it-->
    <h1 class="text-center navButtonNoHover ">Favorite Shows</h1>
    <hr class="border-2 border-emerald-400 w-full">
    @if (count($user->shows) != 0)

        <div class="relative bg-gray-900">
            <div onclick="scrollL()" class="absolute left-0 top-40 text-5xl z-30">
                <i class="fa-solid fa-caret-left bg-emerald-800 rounded-r-xl" style="color: #34d399;"></i>
            </div>
            <div class="grid grid-flow-col overflow-x-scroll nocrollbar no-scrollbar justify-start  " id="scroll">

                @foreach ($shows as $show)
                    <div class=" inline-block border-gray-950 {{ $loop->last ? 'border-0' : 'border-r-2' }}">
                        <div class="relative">
                            <img class=" h-[400px] min-w-[266px] object-cover"
                                src="https://image.tmdb.org/t/p/w500{{ $show->poster_path }}"
                                alt="{!! $show->name !!}'s poster"
                                onerror="this.src='{{ asset('media/placeholder.png') }}'" />

                            <a href="{{ route('shows.show', $show->id) }}"
                                class="absolute bottom-1 right-2 text-emerald-400 hover:text-emerald-500 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300"
                                style="cursor: pointer;">
                                <i class="fa-solid fa-angles-right fa-2x"></i>
                            </a>
                        </div>
                        <h1 class="text-sm font-extrabold text-emerald-400 text-center mt-3">{{ $show->name }}</h1>
                    </div>
                @endforeach
            </div>
            <div onclick="scrollR()" class="absolute right-0 top-40 text-5xl z-30">
                <i class="fa-solid fa-caret-right bg-emerald-800 rounded-l-xl" style="color: #34d399;"></i>
            </div>
        </div>
    @else
        <h1 class="text-center bg-gray-800 navButtonNoHover">{{ $user->username }} doesn't have any shows added </h1>
    @endif

    <br>

    <div class="flex flex-row flex-wrap">
        <div class="w-full md:w-auto">

            <!-- A list of the people who the profile's user is following -->
            <div class=" text-white md:min-w-[300px] md:max-w-[300px] ">
                <h1 class=" text-center text-black text-2xl font-extrabold bg-emerald-700">Follows</h1>
                <div class=" overflow-y-auto scrollbar max-h-[200px] border-r-2 border-emerald-700 bg-gray-900">
                    @if (count($follows) != 0)
                        @foreach ($follows as $follow)
                            <a href="{{ route('follows.show', $follow->id) }}">
                                <p class=" hover:text-emerald-400 navButtonNoHover {{ $loop->even ? 'bg-[#111827] hover:bg-gray-950' : 'bg-[#0b0f18] hover:bg-gray-950' }}"
                                    style="cursor: pointer">
                                    {{ $follow->username }}
                                    @if (strcmp($follow->role, 'Admin') == 0)
                                        <i class=" fa-solid fa-crown mr-5 float-right" style="color: #34d399;"></i>
                                    @else
                                        <i class="fa-solid fa-user mr-5 float-right" style="color: #34d399;"></i>
                                    @endif
                                </p>
                            </a>
                        @endforeach
                    @else
                        <h1 class="text-center bg-gray-800 navButtonNoHover">{{ $user->username }} doesn't follow
                            anyone yet</h1>
                    @endif
                </div>
            </div>

            <!-- A list of the people who follow the profile's user -->
            <div class="text-white  md:min-w-[300px] md:max-w-[300px]">
                <h1 class=" text-center text-black text-2xl font-extrabold bg-emerald-700 ">Followed By</h1>
                <div
                    class=" overflow-y-auto scrollbar max-h-[200px] border-r-2 border-b-2 border-emerald-700  bg-gray-900">
                    @if (count($followers) != 0)
                        @foreach ($followers as $follow)
                            <a href="{{ route('follows.show', $follow->id) }}">
                                <p class="hover:text-emerald-400 navButtonNoHover {{ $loop->even ? 'bg-[#111827] hover:bg-gray-950' : 'bg-[#0b0f18] hover:bg-gray-950' }}"
                                    style="cursor: pointer">
                                    {{ $follow->username }}
                                    @if (strcmp($follow->role, 'Admin') == 0)
                                        <i class=" fa-solid fa-crown mr-5 float-right" style="color: #34d399;"></i>
                                    @else
                                        <i class="fa-solid fa-user mr-5 float-right" style="color: #34d399;"></i>
                                    @endif
                                </p>
                            </a>
                        @endforeach
                    @else
                        <h1 class="text-center bg-gray-800 navButtonNoHover">{{ $user->username }} doesn't have any
                            followers yet </h1>
                    @endif
                </div>
            </div>
        </div>

        <!-- The last 3 rating of the user with some informations about the episode that they rated -->
        <div class="grow text-center w-1/3">
            <h1 class=" text-center text-black text-2xl font-extrabold bg-emerald-700 ">Ratings</h1>


            <div class="inline-block text-white">
                @if (count($ratings) != 0)
                    @foreach ($ratings as $rating)
                        <div
                            class="grid grid-cols-1 2card:grid-cols-2 3card:grid-cols-4 m-2 bg-gray-900 hover:bg-gray-800 border-emerald-700 hover:border-emerald-400 border-t-8">
                            <div class=" flex justify-center ">
                                <div class="mt-2">
                                    <img class=" align-middle pr-2 md:max-w-[200px] w-full"
                                        src="https://image.tmdb.org/t/p/w500{{ $rating->episode_still_path }}"
                                        alt="{{ $rating->episode_name }}'s still"
                                        onerror="this.src='{{ asset('media/logo_xl.png') }}'">
                                </div>
                            </div>

                            <div class="grid grid-flow-row items-center p-2 navButtonNoHover">
                                <div>
                                    <p class="text-2xl md:text-sm 2card:text-2xl 3card:text-2xl font-extrabold">
                                        {{ $rating->show_name }}
                                    </p>
                                    <p class="text-xl 2card:text-base">Season {{ $rating->season_number }} Episode
                                        {{ $rating->episode_number }}</p>
                                </div>
                            </div>
                            <div class="p-2">
                                <p class="text-justify text-sm">{{ $rating->episode_desc }}</p>
                            </div>

                            <div class="grid grid-flow-row items-center border-l-2 p-2 border-emerald-400">
                                <div>
                                    <h1>{{ $user->username }}'s Rating</h1>
                                    <p>10/{{ $rating->user_rating }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h1 class="text-center bg-gray-800 navButtonNoHover">{{ $user->username }} doesn't have any
                        ratings yet </h1>
                @endif
            </div>

            <!-- Option to see all of the user's ratings -->
            <a href="{{ route('ratings.show', $user->id) }}">
                <h2 class="w-full md:w-auto md:transition md:ease-in-out  delay-100 inline-block navButtonNoHover text-2xl bg-emerald-400 hover:bg-emerald-300 md:hover:scale-110 text-black font-extrabold rounded-full my-2"
                    style="cursor: pointer"> See All...</h2>
            </a>
        </div>

    </div>
</div>


<!-- The footer component with the footer tag-->
<x-footer>
</x-footer>


<!-- The script for the horizontal scroll bar -->
<script>
    const scrollContainer = document.querySelector("#scroll");

    scrollContainer.addEventListener("wheel", (evt) => {
        evt.preventDefault();
        scrollContainer.scrollLeft += evt.deltaY / 2;
    });

    function scrollL() {
        scrollContainer.scrollBy({
            top: 0,
            left: -400,
            behavior: 'smooth'
        })
    }

    function scrollR() {
        scrollContainer.scrollBy({
            top: 0,
            left: +400,
            behavior: 'smooth'
        })
    }
</script>

</body>

</html>
