<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<div class="flex flex-col items-center">
    <div
        class="bg-gray-950 m-5 flex flex-col w-full sm:flex-row md:w-10/12 2card:w-5/6 3card:w-4/6 border-b-2 border-emerald-400 relative">

        <!-- The poster and the name of the show-->
        <div class="grid grid-flow-row lg:grid-flow-col">
            <div class="">
                <img class=" h-[90%] sm:h-[300px] min-w-[200px] object-cover m-auto mb-0 pb-0"
                    src="https://image.tmdb.org/t/p/w500{{ $show->poster_path }}" alt="{!! $show->name !!}'s poster"
                    onerror="this.src='{{ asset('media/placeholder.png') }}'" />
            </div>

            <div class="flex place-items-center bg-emerald-400">
                <h1 class="navButtonNoHover font-extrabold  text-black text-2xl w-[200px] border-r-2 border-emerald-400">
                    {{ $show->name }}
                </h1>
            </div>
        </div>

        <div class="flex flex-grow flex-col ">
            <!-- The information about the episode the user wants to rate -->
            <div class="flex-grow ml-2">
                <h2 class="text-center navButtonNoHover text-2xl pb-1">Season
                    {{ $episode->season_number }} Episode
                    {{ $episode->episode_number }}</h2>
                <h3 class="text-center navButtonNoHover text-xl pt-0" {{ isset($episode->name) ? '' : 'hidden' }}>
                    {{ $episode->name }}</h3>

                <div class="flex flex-col lg:flex-row text-white">
                    <img class=" align-middle pr-2 h-[100%] object-contain w-[300px] place-self-center lg:place-self-start"
                        src="https://image.tmdb.org/t/p/w500{{ $episode->still_path }}"
                        alt="Season {{ $episode->season_number }} Episode {{ $episode->episode_number }}'s still"
                        onerror="this.src='{{ asset('media/logo_xl.png') }}'">
                    <div>
                        <p class="text-justify p-2">
                            {{ $episode->overview }}</p>
                    </div>
                </div>
            </div>
            <!-- The form to select a rating from 1-10 for the episode, or if the episode was already rated seting a the old value to default-->
            <form action="{{ route('ratings.store') }}" method="POST">
                @csrf
                @method('POST')
                <div
                    class="bg-gray-800  text-black text-center p-4 w-[100%] m-auto mt-2 mb-2 flex flex-row items-center justify-center">
                    <div class=" " style="width:100px;">
                        <select name="rating"class="bg-black text-emerald-400 border-0">
                            @if($oldRating==null)
                            <option  value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            @else
                            <option {{$oldRating->user_rating==1?'selected':''}} value="1">1</option>
                            <option {{$oldRating->user_rating==2?'selected':''}} value="2">2</option>
                            <option {{$oldRating->user_rating==3?'selected':''}} value="3">3</option>
                            <option {{$oldRating->user_rating==4?'selected':''}} value="4">4</option>
                            <option {{$oldRating->user_rating==5?'selected':''}} value="5">5</option>
                            <option {{$oldRating->user_rating==6?'selected':''}} value="6">6</option>
                            <option {{$oldRating->user_rating==7?'selected':''}} value="7">7</option>
                            <option {{$oldRating->user_rating==8?'selected':''}} value="8">8</option>
                            <option {{$oldRating->user_rating==9?'selected':''}} value="9">9</option>
                            <option {{$oldRating->user_rating==10?'selected':''}} value="10">10</option>
                            @endif

                        </select>

                    </div>
                    <input hidden name="showRecord" value="{{$showRecord->id}}">
                    <input hidden name="oldRating" value="{{isset($oldRating->id)?$oldRating->id:Null}}">
                    <input hidden name="s" value="{{ $episode->season_number}}">
                    <input hidden name="e" value="{{ $episode->episode_number}}">
                    <input hidden name="step" value="{{$step}}">

                    <span class="navButtonNoHover pl-0">Out of 10</span>
                </div>
                <button
                    class="bg-emerald-400 rounded-full text-black text-center p-4 md:transition md:ease-in-out  delay-100 hover:bg-emerald-300 hover:scale-105 w-[80%] m-auto mt-2 mb-2 flex flex-row items-center justify-center"
                    style="cursor: pointer">
                    <span> {{$oldRating==NUll? 'Rate this Episode':'Modify your old Rating'}}</span>

                </button>
            </form>

        </div>


    </div>
</div>

<!-- The footer component with the footer tag-->
<x-footer>
</x-footer>
