<x-head>
</x-head>

<x-navbar>
</x-navbar>

<div class=" text-white">
    <div class="grid w-full grid-flow-row grid-cols-1 justify-items-center m-auto gap-2">

        <div x-data="{ deleteScreen: false }"
            class=" relative 2card:max-w-[1200px] group text-start md:text-justify m-2 flex border border-t-4 border-x-gray-900 border-b-gray-900 border-emerald-400 rounded-br-3xl bg-gray-950  hover:border-b-emerald-400 hover:border-x-emerald-400 flex-row">

            <div class=" p-3">

                <img :class="{ 'blur-sm': deleteScreen }" class=" w-[200px] md:w-[250px] float-left p-0 mr-5 mb-5"
                src="https://image.tmdb.org/t/p/w500{{ $TMDB_api_show->poster_path }}"
                alt="{{ $TMDB_api_show->name }}'s poster" />

                <h5 :class="{ 'blur-sm': deleteScreen }" class=" text-lg font-medium text-neutral-50">
                    {{ $TMDB_api_show->name }}
                </h5>
                <p :class="{ 'blur-sm': deleteScreen }" class=" mb-1 text-base text-neutral-200">
                    {!! $TMDB_api_show->overview !!}
                </p>
                <span @click="deleteScreen= !deleteScreen" class="absolute left-4 top-4" style="cursor: pointer;">
                    <i class=" fa-solid fa-trash fa-2x" style="color: #34d399;"></i>
                </span>


                <div x-show="deleteScreen"
                    class="z-10 navButtonNoHover blur-none absolute right-0 top-1/4 border-2 border-emerald-400 border-r-0 bg-gray-900">
                    <p>Would you like to delete this show from your favourites?
                    </p>
                    <div class="my-2 flex justify-center">
                        <form method="POST" action="{{ route('shows.destroy', $TMDB_api_show->id) }}">
                            @csrf
                            @method('DELETE')
                            <i onclick='this.parentNode.submit();return false;'
                                class="fa-solid fa-circle-check fa-2xl m-5" style="color: #34d399;cursor: pointer;"></i>
                        </form>
                        <i @click="deleteScreen=!deleteScreen" class="fa-solid fa-circle-xmark fa-2xl m-5"
                            style="color: #34d399; cursor: pointer;"></i>
                    </div>
                </div>

                <div :class="{'blur-sm':deleteScreen}" class="m-0">
                    <form method="POST" class="navButtonNoHover mt-5 p-0 pl-5 md:text-start text-center" name="form1" id="form1"
                        action="{{route('shows.update',$id)}}">
                        @csrf
                        @method('PATCH')
                        <div class="inline-block md:flex mb-10">
                            <div class="mt-0">
                                <label class=" text-sm md:text-lg navButtonNoHover" for="season">Last seen Season:</label>
                                <select  :disabled="deleteScreen"  class=" md:w-56 w-full text-emerald-400 bg-gray-800" name="season" id="season">
                                    <option value="" selected>Select Season</option>
                                </select>
                            </div>

                            <span class="">
                                <label class=" text-sm md:text-lg navButtonNoHover" for="episode">Last seen Episode:</label>
                                <select  :disabled="deleteScreen"  class="md:w-56 w-full text-emerald-400 bg-gray-800" name="episode" id="episode">
                                    <option value="" selected>Select Episode</option>
                                </select>
                            </span>
                        </div>
                        <div class="text-end">
                        <input  :disabled="deleteScreen" class=" hover:bg-gray-800 rounded-xl p-2 border-2 border-emerald-400 w-full md:w-60 disabled " style="cursor:pointer" type="submit"
                            value="Done">
                        </div>
                        </form>
                </div>

            </div>
        </div>
    </div>
</div>

<x-footer>
</x-footer>

<script>
    let a = {!! collect($TMDB_api_show) !!};
    let s = [];
    let e = [];

    let current_s = {!! $current_season !!};
    let current_e = {!! $current_episode !!};

    a['seasons'].forEach(element => {
        if (element['season_number']) {
            s.push(element['season_number']);
            e.push(element['episode_count']);
        }
    });

    window.onload = function() {
        var subjectSel = document.getElementById("season");
        var topicSel = document.getElementById("episode");


        console.log(e);
        for (var x in s) {
            if (s[x] == current_s) {
                subjectSel.add(new Option(s[x], s[x], true, true));
            } else {
                subjectSel.add(new Option(s[x], s[x]));
            }
        }

        topicSel.length = 1;
        //display correct values
        for (let i = 1; i <= e[current_s-1]; i++) {
            if (i == current_e) {
                topicSel.add(new Option(i, i, true, true));
            } else {
                topicSel.add(new Option(i, i));
            }
        }

        subjectSel.onchange = function() {
            //empty Chapters- and Topics- dropdowns
            topicSel.length = 1;
            //display correct values
            for (let i = 1; i <= e[this.value-1]; i++) {
                if (i == current_e && this.value == current_s) {
                    topicSel.add(new Option(i, i, true, true));
                } else {
                    topicSel.add(new Option(i, i));
                }
            }
        }
    }
</script>
