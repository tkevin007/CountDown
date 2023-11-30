<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<!-- The form to search the TMDB database-->
<div class="md:grid md:grid-flow-row md:grid-cols-1 md:justify-items-center pb-6">

    <form id="searchForm" class="text-emerald-400 bg-gray-950 p-5 md:w-[550px] w-full  border-t-2 border-emerald-400 pt-0" method="GET"
        action="{{ route('shows.create')}}">
        @csrf
        <label for="searchtext" class="block text-lg mt-2">Show Name</label>
        <div class="flex items-center">
            <input class="bg-gray-900 w-full" name="searchtext" value="{{ $oldSearch ?? '' }}" id="searchtext"
                maxlength="40" />
            <input hidden class="" value='1' name="pageNum" id="pageNum" />
            <a  type="submit" style="cursor:pointer;" onclick='this.parentNode.parentNode.submit();return false;' class="ml-4">
                <i class=" fa-solid fa-magnifying-glass fa-2xl" style="color: #34d399;"></i>
            </a>
        </div>
    </form>
</div>


@if ($results != [])

<!-- The results get displayed with some infos about the shows with option to add them to the favourites if not already added-->
    <div class="relative text-white">
        <div
            class="grid w-full grid-flow-row grid-cols-2 md:grid-cols-4 2card:grid-cols-5 3card:grid-cols-6 justify-items-center m-auto gap-2">

            @foreach ($results->results as $res)
                <div
                    class="flex flex-row justify-center w-[95%] relative group m-2 border border-t-4 border-x-gray-900 border-b-gray-900 border-emerald-400 rounded-br-3xl
                    hover:border-b-emerald-400 hover:border-x-emerald-400   {{in_array($res->id,$existingShows)?"bg-slate-800":"bg-gray-950"}}">
                    <div class=" p-3">

                        <img :class="{ 'blur-sm': deleteScreen }" class=" w-[200px] md:w-[250px] mb-5"
                            src="https://image.tmdb.org/t/p/w500{{ $res->poster_path }}"
                            alt="{{ $res->name }}'s poster"
                            onerror="this.src='{{ asset('media/placeholder.png') }}'" />

                        <h5 :class="{ 'blur-sm': deleteScreen }"
                            class="text-center text-lg font-medium text-neutral-50 mb-10">
                            {{ $res->name }}
                        </h5>
                        <form {{in_array($res->id,$existingShows)?"hidden":""}}  method="POST" class="absolute left-4 bottom-4 text-emerald-400 hover:text-emerald-300"
                            action="{{route('shows.store',$res->id)}}" style="cursor: pointer;">
                            @csrf
                            @method('POST')
                            <input hidden name="show_id" id="show_id" value="{{$res->id}}"/>
                            <button type="submit"><i class=" fa-solid fa-add fa-2x"></i></button>
                        </form>
                        <a href="{{route('shows.show',$res->id)}}">
                            <i class="absolute right-4 bottom-4 text-emerald-400 hover:text-emerald-300 fa-solid fa-chevron-right fa-2x"></i>
                        </a>

                        <span {{in_array($res->id,$existingShows)?"":"hidden"}}  class="text-xs absolute left-4 bottom-4 text-emerald-400 hover:text-emerald-300">
                            Show Already Added
                        </span>

                    </div>
                </div>
            @endforeach

        </div>
    </div>

<!-- Pagination controls if there are more than 20 results there will be multiple pages to paginate through-->
    <div class=" text-center text-emerald-400 ">
        <div class="inline-block  m-2 md:m-10 p-5 border-2 border-emerald-400 bg-gray-950 ">

            <p class="m-5 inline md:text-base text-sm navButtonNoHover"> Showing
                {{ ($results->page - 1) * 20 + 1 }}-{{ $results->page == $results->total_pages ? $results->total_results : $results->page * 20 }}
                results out of {{ $results->total_results }}</p>


            @if ($results->page-1>=1)
            <a class="float-left p-3 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-200 rounded-full hover:bg-gray-900"
            onclick="document.getElementById('searchtext').value='{{$oldSearch}}' ;document.getElementById('pageNum').value='{{$results->page-1}}';document.getElementById('searchForm').submit()"
            style="cursor: pointer;">

            <i class="inline fa-solid fa-chevron-left fa-2xl"></i>
            </a>

            @endif

            @if ($results->page+1<=$results->total_pages)

            <a class="p-3 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-200 rounded-full hover:bg-gray-900 float-right"
               style="cursor: pointer;"
               onclick="document.getElementById('searchtext').value='{{$oldSearch}}' ;document.getElementById('pageNum').value='{{$results->page+1}}';document.getElementById('searchForm').submit()"
               >
                <i class="inline fa-solid fa-chevron-right fa-2xl"></i>
            @endif
            </a>
        </div>
    </div>

@endif




<!-- The footer component with the footer tag-->
<x-footer>
</x-footer>

