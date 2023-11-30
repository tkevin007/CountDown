<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<!--An alertbox stating successfull admin opertation -->
@if (Session::has('adminOperation'))
<x-alertbox primaryText="Success! " secondaryText="{!!Session::get('adminOperation')!!}">
</x-alertbox>
@endif

<!-- The container for all the content on the page -->
<div class="bg-gray-950 md:w-5/6 w-full m-auto mt-0 border-4 border-black">

    <h1 class="bg-emerald-400 text-black p-5 mb-1 text-2xl md:mb-2  "><i class="fa-solid fa-crown mr-5"
            style="color: #000000;"></i>Admin Panel
    </h1>

    <!-- Statistics about the site -->
    <div class="flex flex-row flex-wrap justify-around text-white mt-2 md:mt-0">
        <span>
            <b class="text-white">{{ $userCount }}</b> Total Users
        </span>
        <span>
            <b class="text-white">{{ $showsCount }}</b> Total Shows
        </span>
        <span>
            <b class="text-white">{{ $ratingsCount }}</b> Total Ratings
        </span>
        <span>
            <b class="text-white">{{ $followsCount }}</b> Total Follows
        </span>
    </div>

    <!-- The TOP5 shows from our DB and their avg ratings -->
    <h1 class="text-center navButtonNoHover "> TOP 5 Added Shows</h1>
    <hr class="border-2 border-emerald-400 w-full">
    @if (count($popshows) != 0)

        <div class="relative bg-gray-900">
            <div onclick="scrollL()" class="absolute left-0 top-40 text-5xl z-30">
                <i class="fa-solid fa-caret-left bg-emerald-800 rounded-r-xl" style="color: #34d399;"></i>
            </div>
            <div class="grid grid-flow-col overflow-x-scroll nocrollbar no-scrollbar justify-start  " id="scroll">

                @foreach ($popshows as $show)
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
                        <div class="flex flex-row flex-wrap justify-around text-white mt-2 md:mt-0">
                            <span>
                                <b class="text-white">{{ $popshowsRatingCount[$loop->index] }}</b> Total Ratings
                            </span>
                            <span>
                                <b class="text-white">{{ $popshowsRatingsAVG[$loop->index] }}</b> Avarage
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div onclick="scrollR()" class="absolute right-0 top-40 text-5xl z-30">
                <i class="fa-solid fa-caret-right bg-emerald-800 rounded-l-xl" style="color: #34d399;"></i>
            </div>
        </div>
    @else
        <h1 class="text-center bg-gray-800 navButtonNoHover">No shows have been added! </h1>
    @endif

    <!-- The avg rating table with the rating difference -->
    <h1 class="bg-emerald-400 text-black p-5  mt-5 mb-1 text-2xl md:mb-2  text-center font-extrabold">Top 5 Shows AVG.
        ratings</h1>
    <div
        class="bg-gray-800 p-5 mb-1  md:mt-2 md:mb-2 text-center 2card:text-2xl md:text-sm text-2xl grid justify-items-center md:justify-normal md:grid-cols-none">
        <div class="grid grid-flow-row md:grid-flow-col justify-between content-center justify-items-center">
            <span class="flex"> <img class="mx-5 w-48" src="{{ asset('media/TMDB_long.svg') }}"></span>
            <span class="flex text-emerald-400 p-1"> {{ $TMDBAVG }}</span>
            @if ($diff > 0)
                <p class="flex items-center" style="color:green;font-family: 'Courier New', monospace;">
                    +{{ round($diff, 2) }}</p>
            @else
                <p class="flex items-center" style="color:red;font-family: 'Courier New', monospace;">
                    {{ round($diff, 2) }}</p>
            @endif
            <span class="flex text-emerald-400 p-1">{{ $CDAVG }}</span>
            <span class="flex"><img class="mx-5 w-48 " src="{{ asset('media/logo.png') }}"></span>
        </div>
    </div>

    <!-- The section for admin operations -->
    <div class="">
        <!-- The list of users for the add/remove admin section -->
        <h1 class=" text-center text-2xl font-extrabold bg-emerald-700">Change Role</h1>
        <div class=" overflow-y-auto scrollbar max-h-[200px] border-r-2 border-emerald-700 bg-gray-900">
            @if (count($users) > 1)
                @foreach ($users as $user)
                    @if ($user->id != Auth::id())
                        <div
                            class="p-2 m-2 {{ $loop->even ? 'bg-[#111827] hover:bg-gray-950' : 'bg-[#0b0f18] hover:bg-gray-950' }}">
                            <a href="{{ route('follows.show', $user->id) }}">
                                <p class="inline hover:text-emerald-400 navButtonNoHover" style="cursor: pointer">
                                    {{ $user->username }}</p>
                                @if (strcmp($user->role, 'Admin') == 0)
                                    <i class="fa-solid fa-crown mr-5 float-left" style="color: #34d399;"></i>
                                @else
                                    <i class="fa-solid fa-user mr-5 float-left" style="color: #34d399;"></i>
                                @endif
                            </a>
                            <div class="md:float-right grid grid-flow-col md:grid-none justify-center mt-2 md:mt-0">
                                <form method="POST" action="{{ route('admin.update', $user->id) }}"
                                    class="w-56 text-center border-emerald-400 border-2 rounded-xl text-white inline p-1 ">
                                    @csrf
                                    @method('PATCH')
                                    @if (strcmp($user->role, 'Admin') == 0)
                                        <button class="" type="submit"> Remove Admin</button>
                                    @else
                                        <button class="" type="submit"> Make Admin</button>
                                    @endif
                                </form>
                                <form method="POST"
                                    onsubmit="return confirm('Are you sure you want to Ban {{ $user->username }}?')"
                                    action="{{ route('admin.destroy', $user->id) }}"
                                    style="border-color:lightcoral ; background-color: lightcoral"
                                    class="text-center rounded-lg w-[150px] text-black  border-2 ml-2 font-extrabold inline p-1 ">
                                    @csrf
                                    @method('DELETE')
                                    <button class="" type="submit"> Ban User</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <h1 class="text-center bg-gray-800 navButtonNoHover">There are no Users </h1>
            @endif
        </div>

        <!-- The list of banned users with the option for unban -->
        <div class="">
            <h1 class=" text-center text-2xl font-extrabold bg-emerald-700">Unban Users</h1>
            <div class=" overflow-y-auto scrollbar max-h-[100px] border-r-2 border-emerald-700 bg-gray-900">
                @if (count($bannedUsers) > 0)
                    @foreach ($bannedUsers as $user)
                        <div
                            class="p-2 m-2 {{ $loop->even ? 'bg-[#111827] hover:bg-gray-950' : 'bg-[#0b0f18] hover:bg-gray-950' }}">
                            <p class="inline hover:text-emerald-400 navButtonNoHover" style="cursor: pointer">
                                {{ $user->username }}</p>
                            <i class="fa-solid fa-user mr-5 float-left" style="color: #34d399;"></i>
                            <form method="POST"
                                onsubmit="return confirm('Are you sure you want to Unban {{ $user->username }}?')"
                                action="{{ route('admin.store') }}"
                                style="border-color:lightgreen ; background-color: lightgreen"
                                class="float-right text-center rounded-lg w-[150px] text-black  border-2 ml-2 font-extrabold inline p-1 ">
                                @csrf
                                @method('POST')
                                <button class="" type="submit"> Unban User</button>
                                <input value="{{ $user->id }}" name="id" hidden>
                            </form>
                        </div>
                    @endforeach
                @else
                    <h1 class="text-center bg-gray-800 navButtonNoHover">There are no Banned Users </h1>
                @endif
            </div>
        </div>

        <!-- The listing of incoming messages (only the Reports) -->
        <div class="mt-5">
            <h1 class=" text-center text-2xl font-extrabold bg-emerald-700">Reports</h1>
            <div class=" overflow-y-auto scrollbar max-h-[300px] border-r-2 border-emerald-700 bg-gray-900">
                @if (count($reports) > 0)
                    @foreach ($reports as $report)
                        <div
                            class="text-justify md:grid grid-flow-col p-2 m-2 text-white {{ $loop->even ? 'bg-[#111827] hover:bg-gray-950' : 'bg-[#0b0f18] hover:bg-gray-950' }}">
                            <h1 class="w-60 inline hover:text-emerald-400 navButtonNoHover" style="cursor: pointer">
                                {{ $report->user->username?? "<Deleted User>" }}
                            </h1>
                            <div>
                                <p class="">{{ $report->message }}</p>
                            </div>
                            <form method="POST" action="{{ route('report.update', $report) }}"
                                style="{{ strcmp($report->status, 'Fixed') == 0 ? 'border-color:lightgreen ; background-color: lightgreen' : 'border-color:lightcoral ; background-color: lightcoral' }}"
                                class="justify-self-end cursor-pointer float-right text-center rounded-lg w-[150px] text-black  border-2 ml-2 font-extrabold inline p-1 "
                                onclick=" if (confirm('Are you sure you want change status?')==1) this.submit()">
                                @csrf
                                @method('PATCH')

                                @if (strcmp($report->status, 'Fixed') != 0)
                                    <button class="w-32" type="submit"> Mark as Fixed</button>
                                @else
                                    <button class="w-32" type="submit"> Mark as Unfixed</button>
                                @endif
                                <input value="{{ $report->status }}" name="status" hidden>
                            </form>

                            @if (strcmp($report->status, 'Fixed') == 0)
                                <div class="justify-self-end ml-2 w-36 my-2 md:my-0 place-self-center ">
                                    <i class="fa-solid fa-circle-check" style="color: #34d399;"></i><span>Fixed</span>
                                </div>
                            @else
                                <div class="justify-self-end ml-2 w-36 my-2 md:my-0 place-self-center">
                                    <i class="fa-solid fa-circle-xmark" style="color: #34d399;"></i><span>Not
                                        Fixed</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <h1 class="text-center bg-gray-800 navButtonNoHover">There are no Reports </h1>
                @endif
            </div>
        </div>

        <!-- The listing of incoming messages (only the Messages) -->
        <div class="mt-5">
            <h1 class=" text-center text-2xl font-extrabold bg-emerald-700">Messages</h1>
            <div class=" overflow-y-auto scrollbar max-h-[300px] border-r-2 border-emerald-700 bg-gray-900">
                @if (count($messages) > 0)
                    @foreach ($messages as $message)
                        <div
                            class="text-justify md:grid grid-flow-col p-2 m-2 text-white {{ $loop->even ? 'bg-[#111827] hover:bg-gray-950' : 'bg-[#0b0f18] hover:bg-gray-950' }}">
                            <h1 class="w-80 inline hover:text-emerald-400 navButtonNoHover" style="cursor: pointer">
                                {{ $message->user->username?? "<Deleted User>" }} {{ isset($message->user->email) ?"(".$message->user->email.")": "" }}
                            </h1>
                            <div>
                                <p class="">{{ $message->message }}</p>
                            </div>
                            <form method="POST" action="{{ route('report.update', $message) }}"
                                style="{{ $message->read == 1 ? 'border-color:lightgreen ; background-color: lightgreen' : 'border-color:lightcoral ; background-color: lightcoral' }}"
                                class="justify-self-end cursor-pointer float-right text-center rounded-lg w-[150px] text-black  border-2 ml-2 font-extrabold inline p-1 "
                                onclick=" if (confirm('Are you sure you want change status?')==1) this.submit()">
                                @csrf
                                @method('PATCH')

                                @if ( $message->read != 1)
                                    <button class="w-32" type="submit"> Mark as Read</button>
                                @else
                                    <button class="w-32" type="submit"> Mark as Unread</button>
                                @endif
                                <input value="{{ $report->read }}" name="status" hidden>
                            </form>

                        </div>
                    @endforeach
                @else
                    <h1 class="text-center bg-gray-800 navButtonNoHover">There are no Messages </h1>
                @endif
            </div>
        </div>
    </div>
</div>

<!--footer component -->
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
