<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<!-- Message about the deletion of a show -->
@if (Session::has('deleted'))
<x-alertbox primaryText="Success! " secondaryText="{{Session::get('deleted')}} has beed deleted.">
</x-alertbox>
@endif

<!-- Message about the modification of a show -->
@if (Session::has('modified'))
<x-alertbox primaryText="Success! " secondaryText="{{Session::get('modified')}} has beed modified.">
</x-alertbox>
@endif

@if ((Auth::user()->shows)->count()==0)
<!-- Message if there are no added shows by the user -->
<x-alertbox primaryText="No Shows added yet!" secondaryText="You can add new shows with the plus icon in the bottom right corner." closable="false">
</x-alertbox>
@endif

<!-- Displaying each show with the help of the showCard component -->
<div class="relative text-white">
       <div class="grid w-full grid-flow-row grid-cols-1 2card:grid-cols-2 3card:grid-cols-3 justify-items-center m-auto gap-2">
        @foreach ($TMDB_api_shows as $s )
        <x-showCard poster="{{$s->poster_path}}" title="{{$s->name}}" desc="{{$s->overview}}" id="{{$show_ids[$loop->index]}}" tmdbid="{{$s->id}}">
            </x-showCard>
        @endforeach
</div>


<a href="{{route('shows.create')}}" class=" z-30 fixed right-5 bottom-2 text-7xl" style="cursor: pointer;">
    <i class="fa-solid fa-circle-plus text-emerald-400 hover:text-emerald-500 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300" ></i>
</a>


</div>

<!-- The footer component with the footer tag-->
<x-footer>
</x-footer>


