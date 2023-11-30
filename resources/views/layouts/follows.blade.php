<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<!-- Displaying the follows of the user if there is any-->
@if (count($followedUsers) != 0)

    <div x-data="{ filter: 0 }"
        class="grid grid-flow-row md:grid-flow-col md:grid-cols-[150px_auto] 2card:grid-cols-[350px_auto]">

        <!-- The list of followed users, acting as filters for the right side of the page showing the ratings -->
        <div x-data="{ open: false }" @mouseleave="open=false" style="user-select: none;"
            class="text-white bg-slate-900 border-b-2 border-emerald-400 md:border-b-0 md:border-l-4">

            <p x-on:click="open=!open" style="cursor: pointer;"
                class="md:hidden navButtonNoHover text-center p-3 hover:bg-gray-950">Filter By Users</p>
            <div :class="open ? 'block' : 'hidden'" class="md:block mb-3 md:mb-0">

                <p x-on:click="filter=0"
                    :class="filter == 0 ? 'bg-emerald-400 text-black text-2xl font-extrabold hover:none' :
                        'bg-slate-800 hover:bg-gray-950'"
                    class=" text-center text-2xl md:max-w-[400px] p-3 md:p-5 " style="cursor: pointer;">
                    Show All
                </p>

                <!-- The users who are followed by the Auth user -->
                @foreach ($followedUsers as $followedUser)
                    <div x-on:click="filter='{{ $followedUser->id }}'"
                        :class="filter == '{!! $followedUser->id !!}' ?
                            'bg-emerald-400 text-black text-lg font-extrabold hover:none' :
                            'hover:bg-gray-950 {{ $loop->index % 2 == 0 ? 'bg-gray-900' : 'bg-slate-800' }} '"
                        class=" md:max-w-[400px] p-3 md:p-5 flex flex-row flex-wrap" style="cursor: pointer;">

                        <p class="flex-grow p-2">{{ $followedUser->username }}</p>

                        <a href={{ route('follows.show', $followedUser->id) }}>
                            <p class="p-2 rounded-xl text-center text-sm md:text-base"
                                :class="filter == '{!! $followedUser->id !!}' ?
                                    'bg-emerald-800 text-white hover:bg-black hover:text-white' :
                                    'bg-emerald-700 hover:bg-emerald-400 text-white hover:text-black font-bold'">
                                View Profile</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="">
            @if (count($followHistory) != 0)
                @foreach ($followHistory as $historyLog)
                <!-- The right side of the page with the history of the Auth user's followed account's ratings ordered by time of rating, with optional filter options -->
                    <div :class="[filter == '{{ $historyLog->user_id }}' || filter == 0 ? 'block' : 'hidden', !hover ?
                        'bg-gray-950 text-white' : 'bg-gray-800'
                    ]"
                        border-emerald-400 border-2' x-data="{ hover: false }" @mouseover="hover=true"
                        @mouseout="hover=false" class=" p-2 mb-2 ml-2 navButtonNoHover text-white ">
                        <div :class="!hover ? 'bg-emerald-800 text-white' : 'bg-emerald-400 text-gray-800'"
                            class="">
                            <p class="text-base">
                                <span class="p-0 text-2xl">{{ $historyLog->user->username }}</span>
                                rated at {{ $historyLog->updated_at }}</span>
                            </p>
                        </div>
                        <div class="md:flex md:flex-row p-0">
                            <div class=" md:block 2card:block">
                                <img class="md:min-w-[200px] pr-2 md:max-w-[200px] w-full"
                                    src="https://image.tmdb.org/t/p/w500{{ $historyLog->episode_still_path }}"
                                    alt="{{ $historyLog->episode_name }}'s still"
                                    onerror="this.src='{{ asset('media/logo_xl.png') }}'">
                            </div>
                            <div
                                class="md:text-start text-center md:min-w-[170px] md:max-w-[170px] 2card:min-w-[200px] 2card:mr-5">
                                <p class="text-2xl md:text-base 2card:text-2xl font-extrabold">
                                    {{ $historyLog->show_name }}
                                </p>
                                <p class="text-sm 2card:text-base">Season {{ $historyLog->season_number }} Episode
                                    {{ $historyLog->episode_number }}</p>
                            </div>
                            <div class="flex-grow ">
                                <p class="text-justify text-sm">{{ $historyLog->episode_desc }}</p>
                            </div>

                            <div
                                class=" text-center border-l-2 border-emerald-400 md:min-w-[100px] md:max-w-[100px] pt-5 pr-1 ml-2 mt-2 md:mt-0">
                                <h1>User's Rating</h1>
                                <p>10/{{ $historyLog->user_rating }}</p>

                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <!-- A message if the user's followers haven't rated anything yet, so there is nothing to show on this page -->
                <x-alertbox primaryText="None of your followed users rated any episodes yet!"
                 closable="false">
                </x-alertbox>
            @endif
        </div>

    </div>
@else
    <!-- A message if there are no follows associated with the user-->
    <x-alertbox primaryText="You don't follow anyone!"
        secondaryText="You can following users with the plus icon in the bottom right corner." closable="false">
    </x-alertbox>


@endif

<!-- The add new follow button -->
<a href="{{ route('follows.create') }}" class=" z-30 fixed right-5 bottom-2 text-7xl" style="cursor: pointer;">
    <i
        class="fa-solid fa-circle-plus text-emerald-400 hover:text-emerald-500 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300"></i>
</a>

<!-- The footer component with the footer tag-->
<x-footer>
</x-footer>
