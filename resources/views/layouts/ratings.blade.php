<x-head>
</x-head>

<x-navbar>
</x-navbar>

@if (Session::has('change'))
    <x-alertbox primaryText="{!! Session::get('change') !!}">
    </x-alertbox>

@endif

@if(isset($user))
<div>
    <h1 class="bg-emerald-400 text-black p-5 mb-2 text-2xl  "><i class="fa-solid fa-user mr-5"
        style="color: #000000;"></i>{{ $user }}'s Ratings</h1>
</div>
@endif

@if (count($ratings)!=0)

<div x-data="{ filter: 0 }"
    class="grid grid-flow-row md:grid-flow-col md:grid-cols-[150px_auto] 2card:grid-cols-[350px_auto]">

    <div x-data="{ open: false }" @mouseleave="open=false" style="user-select: none;"
        class="text-white bg-slate-900 border-b-2 border-emerald-400 md:border-b-0 md:border-l-4">

        <p x-on:click="open=!open" style="cursor: pointer;"
            class="md:hidden navButtonNoHover text-center p-3 hover:bg-gray-950">Filter By Shows</p>
        <div :class="open ? 'block' : 'hidden'" class="md:block mb-3 md:mb-0">

            <p x-on:click="filter=0"
                :class="filter == 0 ? 'bg-emerald-400 text-black text-2xl font-extrabold hover:none' :
                    'bg-slate-800 hover:bg-gray-950'"
                class=" text-center text-2xl md:max-w-[400px] p-3 md:p-5 " style="cursor: pointer;">
                Show All
            </p>

            @foreach ($shows as $show)
                <p x-on:click="filter='{{$show->TMDB_show_id}}'"
                    :class="filter == '{!! $show->TMDB_show_id !!}' ? 'bg-emerald-400 text-black text-lg font-extrabold hover:none' :
                        'hover:bg-gray-950 {{ $loop->index % 2 == 0 ? 'bg-gray-900' : 'bg-slate-800' }} '"
                    class=" md:max-w-[400px] p-3 md:p-5   " style="cursor: pointer;">{{ $show->show_name }}</p>
            @endforeach
        </div>
    </div>

    <div class="">
        @foreach ($ratings as $rating)
            <div :class="[filter == '{{ $rating->TMDB_show_id }}' || filter == 0 ? 'block' : 'hidden']"
                class="bg-gray-950 hover:bg-gray-800 p-2 mb-2 ml-2 navButtonNoHover text-white  border-t-8 border-emerald-800 hover:border-emerald-400">


                <div class="md:flex md:flex-row p-0">
                    <div class=" md:block 2card:block">
                        <img class="md:min-w-[200px] pr-2 md:max-w-[200px] w-full"
                            src="https://image.tmdb.org/t/p/w500{{ $rating->episode_still_path }}"
                            alt="{{ $rating->episode_name }}'s still"
                            onerror="this.src='{{ asset('media/logo_xl.png') }}'">
                    </div>
                    <div
                        class="md:text-start text-center md:min-w-[170px] md:max-w-[170px] 2card:min-w-[200px] 2card:mr-5">
                        <p class="text-2xl md:text-base 2card:text-2xl font-extrabold">{{ $rating->show_name }}</p>
                        <p class="text-sm 2card:text-base">Season {{ $rating->season_number }} Episode
                            {{ $rating->episode_number }}</p>

                           <form action="{{route('watchlist.show',$rating->TMDB_show_id)}}" method="GET">
                            @csrf
                            @method('GET')
                            <i onclick="this.parentNode.submit()" class="fa-solid fa-pen-to-square" style="color: #34d399;"></i>
                            <input hidden name="e" value="{{$rating->episode_number}}"/>
                            <input hidden name="s" value="{{$rating->season_number}}"/>
                            <input hidden name="step" value='0' />
                            </form>
                    </div>
                    <div class="flex-grow ">
                        <p class="text-justify text-sm">{{ $rating->episode_desc }}</p>
                    </div>

                    <div
                        class=" text-center border-l-2 border-emerald-400 md:min-w-[100px] md:max-w-[100px] pt-5 pr-1 ml-2 mt-2 md:mt-0">
                        <h1>{{isset($user)? "Their":"Your"}} Rating</h1>
                        <p>10/{{ $rating->user_rating }}</p>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@elseif (isset($user))

<x-alertbox primaryText="{{$user}} haven't rated any episodes yet!" secondaryText="You can start rating episodes on your watchlist page!" closable="false">
</x-alertbox>

@else

<x-alertbox primaryText="You haven't rated any episodes yet!" secondaryText="You can start rating episodes on your watchlist page!" closable="false">
</x-alertbox>


@endif



<x-footer>
</x-footer>
