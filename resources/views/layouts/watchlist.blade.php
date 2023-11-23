<x-head>
</x-head>

<x-navbar>
</x-navbar>

@if (count($notWatchedShows)==0)
<x-alertbox primaryText="Oh no! " secondaryText="It seems like you dont have anything on your watchlist now.">
</x-alertbox>
@endif

<div class="flex flex-col items-center">
    @foreach ($notWatchedShows as $show)
        <div x-data="{ show: false }"
            class="bg-gray-950 m-5 flex flex-col w-full sm:flex-row md:w-10/12 2card:w-5/6 3card:w-4/6 border-b-2 border-emerald-400 relative">


            <div @click="show = ! show" class="relative sm:absolute sm:top-2 sm:right-2 sm:grid text-center p-5 sm:p-0 sm:grid-flow-row justify-items-end"
                style="cursor:pointer">
                <span  class="mb-2 text-emerald-400 text-lg sm:text-sm md:text-xs">Show Spoilers</span>
                <label class="switch w-[50px]">
                    <p  >
                    <input :checked="show" type="checkbox" >
                    <span   @click="show = ! show" class="slider round"></span>
                    </p>
                  </label>
            </div>

            <div class="grid grid-flow-row lg:grid-flow-col">
                <div class="">
                    <img class=" h-[90%] sm:h-[300px] min-w-[200px] object-cover m-auto mb-0 pb-0"
                        src="https://image.tmdb.org/t/p/w500{{ $show->poster_path }}"
                        alt="{!! $show->name !!}'s poster"
                        onerror="this.src='{{ asset('media/placeholder.png') }}'" />
                </div>

                <div class="flex place-items-center bg-emerald-400">
                    <h1
                        class="navButtonNoHover font-extrabold  text-black text-2xl w-[200px] border-r-2 border-emerald-400">
                        {{ $show->name }}
                    </h1>
                </div>
            </div>
            <div class="flex flex-grow flex-col ">
                <div class="flex-grow ml-2">
                    <h2 class="text-center navButtonNoHover text-2xl pb-1">Season
                        {{ $notWatchedEpisodeDetails[$loop->index]->season_number }} Episode
                        {{ $notWatchedEpisodeDetails[$loop->index]->episode_number }}</h2>
                    <h3 class="text-center navButtonNoHover text-xl pt-0"
                        {{ isset($notWatchedEpisodeDetails[$loop->index]->name) ? $notWatchedEpisodeDetails[$loop->index]->name : 'hidden' }}>
                        {{ $notWatchedEpisodeDetails[$loop->index]->name }}</h3>

                    <div class="flex flex-col lg:flex-row text-white">
                        <img :class="show ? '' : 'blur-lg'"
                            class=" align-middle pr-2 h-[100%] object-contain w-[300px] place-self-center lg:place-self-start"
                            src="https://image.tmdb.org/t/p/w500{{ $notWatchedEpisodeDetails[$loop->index]->still_path }}"
                            alt="Season {{ $notWatchedEpisodeDetails[$loop->index]->season_number }} Episode {{ $notWatchedEpisodeDetails[$loop->index]->episode_number }}'s still"
                            onerror="this.src='{{ asset('media/logo_xl.png') }}'">
                        <div>
                            <p :class="show ? '' : 'blur-sm'" class="text-justify p-2">
                                {{ $notWatchedEpisodeDetails[$loop->index]->overview }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <form
                        class="bg-emerald-400 rounded-full text-black text-center p-4 md:transition md:ease-in-out  delay-100 hover:bg-emerald-300 hover:scale-105 w-[40%] m-auto mt-2 mb-2"
                        style="cursor: pointer" action="{{ route('watchlist.show',$show->id) }}" method="GET">
                        <button type="submit">Mark as Watched & Rate</button>
                    </form>
                </div>
            </div>


        </div>
    @endforeach
</div>

<x-footer>
</x-footer>
