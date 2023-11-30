<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<div>
    <div class="md:grid md:grid-flow-row md:grid-cols-1 md:justify-items-center pb-6">

        <!-- The search bar to look for usernames -->
        <div id="searchForm"
            class="text-emerald-400 bg-gray-950 p-5 md:w-[550px] w-full  border-t-2 border-emerald-400 pt-0">
            <label for="searchtext" class="block text-lg mt-2">Username</label>
            <div class="flex items-center">
                <input class="bg-gray-900 w-full" name="searchtext" value="" id="searchtext" maxlength="40"
                    type="search" />
                <a style="cursor:pointer;" class="ml-4">
                    <i class=" fa-solid fa-magnifying-glass fa-2xl" style="color: #34d399;"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- The container to show result by default it shows all-->
    <div id="results"
        class="grid grid-cols-1 md:grid-cols-2 2card:grid-cols-3 3card:grid-cols-4 justify-items-center">
        @foreach ($users as $user)
            @if ($user->id != Auth::id())
                <!-- Each user's card containing the some info about them-->
                <div
                    class="relative text-white bg-gray-950 m-5 hover:bg-slate-950 w-full md:w-[48vw] 2card:w-[31vw] 3card:w-[24vw]
                        rounded-br-xl">

                    <div class="relative text-center">
                        <div
                            class="grid grid-rows-4 grid-cols-1 spacing w-[80%] shadow-2xl  border-4 border-r-0 border-y-black border-l-black ">
                            <span class="text-xl text-white bg-emerald-700  pt-1">{{ $user->username }}</span>
                            <span class="navButtonNoHover text-base bg-gray-800 pt-3 w-full leading-relaxed"><b
                                    class="text-white">{{ $user->shows->count() }}</b> Shows <b
                                    class="text-white">{{ $user->ratings->count() }}</b> Ratings</span>
                            <span class="navButtonNoHover text-base bg-gray-900  pt-1 h-12 "><i
                                    class="fa-solid fa-calendar-days" style="color: #34d399;"></i> Registered at:
                                {{ $user->created_at }}</span>
                            <span class="navButtonNoHover text-base bg-gray-800  pt-1"><b
                                    class="text-white">{{ $user->follows->count() }}</b> Follows <b
                                    class="text-white">{{ $followers[$loop->index] }}</b> Followers</span>

                            <form action="{{ route('follows.store') }}" method="POST"
                                class="{{in_array($user->id,Auth::user()->follows->pluck('follow_id')->toArray())?"hidden":""}}
                                absolute right-0 top-[50%] -translate-y-1/2 block md:text-center text-end mr-10 md:mr-2 p-3 pt-2 md:p-2 text-emerald-400 transition-colors duration-700 transform hover:bg-emerald-600 hover:text-gray-100 focus:border-4 focus:border-indigo-300 rounded-full"
                                style="cursor: pointer;"
                                onclick="this.submit()">
                                @csrf
                                @method('POST')
                                <input hidden id="user_id" name="user_id" value="{{$user->id}}">
                                <i class="fa-solid fa-add fa-2xl"></i>
                            </form>
                            <p class="absolute -right-4 top-[50%] -translate-y-1/2 -rotate-90 navButtonNoHover p-0
                            {{!in_array($user->id,Auth::user()->follows->pluck('follow_id')->toArray())?"hidden":""}}">
                            Already Added!</p>

                        </div>
                        <a href="{{ route('follows.show', $user->id) }}"
                            class=" md:text-center navButtonNoHover hover:text-emerald-500" style="cursor: pointer;">
                            <p class="inline-block p-2">Open Profile <i class="fa-solid fa-angle-right fa-xl"></i></p>
                        </a>

                    </div>



                </div>
            @endif
        @endforeach
    </div>
</div>

<script>
    //the script to make the searchbar work
    $(document).ready(function() {
        $("#searchtext").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#results div").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>


<!-- The footer component with the footer tag-->
<x-footer>
</x-footer>
