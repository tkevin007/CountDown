<!-- Contains the head tag and settings-->
<x-head>
</x-head>

<!-- Contains the navbar-->
<x-navbar>
</x-navbar>

<!-- An optional message to be displayed -->
@if (Session::has('sent'))
<x-alertbox primaryText="Success! " secondaryText="{!!Session::get('sent')!!}">
</x-alertbox>
@endif

<!-- Show the cards for the shows if there are any shows -->
@if (count($shows) != 0)
    <div class="grid grid-cols-1 md:grid-cols-2 2card:grid-cols-3 3card:grid-cols-4 justify-items-center">
        <!-- The cards containing info about the show-->
        @foreach ($shows as $show)
            <div class="relative text-white bg-gray-950 m-5 border-b-2 hover:border-emerald-800 hover:bg-slate-950 border-emerald-400 w-full md:w-[48vw] 2card:w-[31vw] 3card:w-[24vw] rounded-br-xl">

                <!-- All the infos about the show-->
                <div class="relative text-center">
                    <img class="md:min-w-[200px] m-auto md:ml-0 md:max-w-[250px] w-[60vw]"
                        src="https://image.tmdb.org/t/p/w500{{ $show->poster_path }}" alt="{{ $show->name }}'s still"
                        onerror="this.src='{{ asset('media/placeholder.png') }}'">

                    <div
                        class="absolute right-0 top-[50%] -translate-y-1/2 flex flex-col place-content-center w-[250px] shadow-2xl border-4 border-r-0 border-y-black border-l-black">
                        <p class="text-3xl text-white bg-emerald-600">{{ $show->name }}</p>
                        <p class="text-base text-white bg-emerald-800">Season
                            {{ $show->next_episode_to_air->season_number }}
                            Episode {{ $show->next_episode_to_air->episode_number }}</p>
                        <p class="text-lg bg-gray-800"><i>Release Date</i>
                            <br>{{ $show->next_episode_to_air->air_date }}
                        </p>
                    </div>

                <!-- The countdown timer-->
                </div>
                <div id="{{ $show->id }}">
                    <div class="text-center navButtonNoHover mb-0">
                        <span class="days"></span><span> Days</span>

                        <br>

                        <span class="hours"></span><span> Hours</span>
                        <span class="minutes"></span><span> Minutes</span>
                        <span class="seconds"></span><span> Seconds</span>
                    </div>
                    <div class="text-center navButtonNoHover font-extrabold text-2xl p-0">
                        <span class="deadline"></span>
                    </div>
                </div>

                <!-- Link to open the details of the show-->
                <a href="{{ route('shows.show', $show->id) }}"
                    class="md:absolute md:right-7 md:top-2 block md:text-center text-end mr-10 md:mr-0 p-3 pt-0 md:p-2 text-emerald-400 hover:text-emerald-500 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300"
                    style="cursor: pointer;">
                    <i class="fa-solid fa-angles-right fa-2x"></i>
                </a>

            </div>
        @endforeach
    </div>
@else
   <!-- Message if there are no release dates for any of the user's shows -->
    <x-alertbox primaryText="Your CoundDown list is empty. "
        secondaryText=" This page will update if there is a new release date for any of you shows!" closable="false">
    </x-alertbox>

@endif



<script>

    //if the document loaded, than change the values to the correct ones
    $(document).ready(function() {
        function getTimeRemaining(endtime) {
            let t = Date.parse(endtime) - Date.parse(new Date());
            let seconds = Math.floor((t / 1000) % 60);
            let minutes = Math.floor((t / 1000 / 60) % 60);
            let hours = Math.floor((t / (1000 * 60 * 60)) % 24);
            let days = Math.floor(t / (1000 * 60 * 60 * 24));
            return {
                'total': t,
                'days': days,
                'hours': hours,
                'minutes': minutes,
                'seconds': seconds
            };
        }

        //defining the query selectors
        function initializeClock(id, endtime) {
            let clock = document.getElementById(id);
            let daysSpan = clock.querySelector('.days');
            let hoursSpan = clock.querySelector('.hours');
            let minutesSpan = clock.querySelector('.minutes');
            let secondsSpan = clock.querySelector('.seconds');
            let deadlineSpan = clock.querySelector('.deadline');
            let timeinterval = setInterval(updateClock, 1000);

            //The countdown function runs every second per show
            function updateClock() {
                let t = getTimeRemaining(endtime);

                daysSpan.innerHTML = t.days;
                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

                if (t.total <= 0) {
                    deadlineSpan.innerHTML = "Released NOW!";
                    clearInterval(timeinterval);
                }
            }

            updateClock();

        }

        //Finding the release date of the show
        let shows = @json($shows);
        shows.forEach(show => {
            initializeClock(show['id'], new Date(Date.parse(new Date()) + (new Date(show[
                'next_episode_to_air']['air_date']) - Date.now())));

        });

    });
</script>

<!-- The footer component with the footer tag-->
<x-footer>
</x-footer>

</body>
</html>
