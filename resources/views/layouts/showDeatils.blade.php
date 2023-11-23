<x-head>
</x-head>
<x-navbar>
</x-navbar>


@if (Session::has('followed'))
    <x-alertbox primaryText="Success! " secondaryText="You started following {{ Session::get('followed') }}">
    </x-alertbox>
@endif

@if (Session::has('unfollowed'))
    <x-alertbox primaryText="Success! " secondaryText="You stopped following {{ Session::get('unfollowed') }}">
    </x-alertbox>
@endif

<div class="bg-gray-950 2card:w-4/5 3card:w-4/6 w-full m-auto mt-0 border-4 border-black">

    <h1 class="bg-emerald-400 text-black p-5 md:mb-2 text-2xl  ">{{ $show->name }}
        <div class="hidden md:inline">
            @if (!$doFollow)
                <form action="{{ route('shows.store') }}" method="POST"
                    class="transition ease-in-out  delay-100 hover:scale-110 hover:bg-gray-900 float-right bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                    style="cursor: pointer" onclick="this.submit()">
                    @csrf
                    @method('POST')
                    <input hidden name="show_id" id="show_id" value="{{ $show->id }}">
                    Add To Favorites <i class="fa-solid fa-add" style="color: #34d399;"></i>
                </form>
            @elseif($doFollow)
                <form action="{{ route('shows.destroy', $followRecordId) }}" method="POST"
                    class="transition ease-in-out  delay-100 hover:scale-110 hover:bg-gray-900 float-right bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                    style="cursor: pointer" onclick="this.submit()">
                    @csrf
                    @method('DELETE')
                    Remove from Favorites <i class="fa-solid fa-trash" style="color: #34d399;"></i>
                </form>
                <form action="{{ route('shows.edit', $followRecordId) }}" method="GET"
                    class="transition ease-in-out  delay-100 hover:scale-110 hover:bg-gray-900 float-right bg-gray-950 rounded-lg p-2 mx-2 text-emerald-400 prevent-select"
                    style="cursor: pointer" onclick="this.submit()">
                    @csrf
                    @method('GET')
                    Edit <i class="fa-solid fa-pen-to-square" style="color: #34d399;"></i>
                </form>
            @endif

        </div>
    </h1>

    <h1 class=" m-2 text-white mb-2 text-2xl grid justify-items-center content-center md:hidden">
        @if (!$doFollow)
            <form action="{{ route('shows.store') }}" method="POST"
                class="border-emerald-400 border-2  text-center transition ease-in-out  w-1/2 delay-100 hover:scale-110 hover:bg-gray-900  bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                style="cursor: pointer" onclick="this.submit()">
                @csrf
                @method('POST')
                <input hidden name="show_id" id="show_id" value="{{ $show->id }}">
                Add To Favorites <i class="fa-solid fa-add" style="color: #34d399;"></i>
            </form>
        @elseif($doFollow)
            <div class="flex flex-row">
                <form action="{{ route('shows.destroy', $followRecordId) }}" method="POST"
                    class="border-emerald-400 border-2  text-center transition ease-in-out delay-100 hover:scale-110 hover:bg-gray-900 bg-gray-950 rounded-lg p-2 text-emerald-400 prevent-select"
                    style="cursor: pointer" onclick="this.submit()">
                    @csrf
                    @method('DELETE')
                    Remove from Favorites <i class="fa-solid fa-trash" style="color: #34d399;"></i>
                </form>
                <form action="{{ route('shows.edit', $followRecordId) }}" method="GET"
                    class="border-emerald-400 border-2  text-center transition ease-in-out delay-100 hover:scale-110 hover:bg-gray-900 bg-gray-950 rounded-lg p-2 ml-4 text-emerald-400 prevent-select"
                    style="cursor: pointer" onclick="this.submit()">
                    @csrf
                    @method('GET')
                    Edit <i class="fa-solid fa-pen-to-square" style="color: #34d399;"></i>
                </form>
            </div>
        @endif
    </h1>


    <div class="grid grid-flow-row md:grid-flow-col text-white w-full">
        <div class="grid justify-items-center mb-5 md:mr-3">
            <img class=" h-[400px] min-w-[266px] object-cover"
                src="https://image.tmdb.org/t/p/w500{{ $show->poster_path }}" alt="{!! $show->name !!}'s poster"
                onerror="this.src='{{ asset('media/placeholder.png') }}'" />
        </div>

        <div class="flex-grow flex justify-items-center ">
            <div class="grid grid-flow-row">
                <p class="text-justify">{{ $show->overview }}</p>
                <div class="w-full place-self-end text-center mb-2">
                    @foreach ($show->genres as $genre )
                        <p class="float-left p-1 m-1 bg-emerald-500 text-black text-sm">{{$genre->name}}</p>
                    @endforeach
                </div>
            </div>
            <div class="navButtonNoHover bg-emerald-400 text-black font-extrabold m-2">
                <p class="text-center">Avg. Rating <span class="text-2xl">{{ $show->vote_average }}</span> out of 10
                </p>
            </div>
        </div>
        <table class=" text-white border-gray-800 border-4  text-left m-4  ">
            <tbody class="[&>*:nth-child(even)]:bg-slate-900">
                <tr {{ count($show->created_by) != 0 ? '' : 'hidden' }}>
                    <th>Created By</th>
                    <td>
                        @foreach ($show->created_by as $creator)
                            <p> {{ $creator->name }}</p>
                        @endforeach
                    </td>
                </tr>
                <tr {{ isset($show->homepage) ? '' : 'hidden' }}>
                    <th>Homepage</th>
                    <td><a id="link" class="text-gray-400 font-extrabold underline hover:text-gray-300"
                            href="{{ $show->homepage }}">{{ isset($show->networks[0]->name) ? $show->networks[0]->name : 'Link' }}
                        </a></td>
                </tr>
                <tr {{ isset($show->first_air_date) ? '' : 'hidden' }}>
                    <th>First Air Date</th>
                    <td>{{ $show->first_air_date }}</td>
                </tr>
                <tr {{ isset($show->last_air_date) ? '' : 'hidden' }}>
                    <th>Last Episode Air Date</th>
                    <td>{{ $show->last_air_date }}</td>
                </tr>
                <tr {{ isset($show->number_of_seasons) ? '' : 'hidden' }}>
                    <th>Number of Seasons</th>
                    <td>{{ $show->number_of_seasons }}</td>
                </tr>
                <tr {{ isset($show->number_of_episodes) ? '' : 'hidden' }}>
                    <th>Number of Episodes</th>
                    <td>{{ $show->number_of_episodes }}</td>
                </tr>
                <tr {{ count($show->origin_country) != 0 ? '' : 'hidden' }}>
                    <th>Origin Country</th>
                    <td>
                        @foreach ($show->origin_country as $country)
                            <p> {{ $country }}</p>
                        @endforeach
                    </td>
                </tr>
                <tr {{ isset($show->status) ? '' : 'hidden' }}>
                    <th>Status</th>
                    <td>{{ $show->status }}</td>
                </tr>
                <tr {{ isset($show->type) ? '' : 'hidden' }}>
                    <th>Type</th>
                    <td>{{ $show->type }}</td>
                </tr>
            </tbody>
        </table>
    </div>


    <div class="w-full md:flex md:flex-row">
        <div class=" text-white md:min-w-[300px] md:max-w-[300px] pb-5 md:pb-0 ">
            <h1 class=" text-center text-black text-2xl font-extrabold bg-emerald-700">Users following</h1>
            <div class=" overflow-y-auto scrollbar max-h-[200px] border-2 border-emerald-700 bg-gray-900">
                @if (count($users) != 0)
                    @foreach ($users as $user)
                        <a href="{{ route('follows.show', $user->id) }}">
                            <p class=" hover:text-emerald-400 navButtonNoHover {{ $loop->even ? 'bg-[#111827] hover:bg-gray-950' : 'bg-[#0b0f18] hover:bg-gray-950' }}"
                                style="cursor: pointer">
                                {{ $user->username }}
                                @if (strcmp($user->role, 'Admin') == 0)
                                <i class=" fa-solid fa-crown mr-5 float-right" style="color: #34d399;"></i>
                            @else
                                <i class="fa-solid fa-user mr-5 float-right" style="color: #34d399;"></i>
                            @endif
                            </p>
                        </a>
                    @endforeach
                @else
                    <h1 class="text-center bg-gray-800 navButtonNoHover">{{ $show->name }} doesn't have any followers yet</h1>
                @endif
            </div>
        </div>

        <div class="relative border-2 border-gray-800 bg-gray-900 overflow-hidden">
            <div onclick="scrollL()" class="absolute left-0 top-24 text-5xl z-30">
                <i class="fa-solid fa-caret-left bg-emerald-800 rounded-r-xl" style="color: #34d399;"></i>
            </div>
            <div class="grid grid-flow-col overflow-x-scroll nocrollbar no-scrollbar justify-start  " id="scroll">
                @foreach ($showImages->backdrops as $img)
                    <div class=" inline-block border-gray-950 {{ $loop->last ? 'border-0' : 'border-r-2' }}">
                        <div class="relative">
                            <img class=" min-w-[400px] max-w-[400px] object-cover"
                                src="https://image.tmdb.org/t/p/w500{{ $img->file_path }}"
                                alt="{!! $show->name !!}'s poster"
                                onerror="this.src='{{ asset('media/placeholder.png') }}'" />
                        </div>
                    </div>
                @endforeach
            </div>
            <div onclick="scrollR()" class="absolute right-0 top-24 text-5xl z-30">
                <i class="fa-solid fa-caret-right bg-emerald-800 rounded-l-xl" style="color: #34d399;"></i>
            </div>
        </div>

    </div>
</div>



<x-footer>
</x-footer>

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
