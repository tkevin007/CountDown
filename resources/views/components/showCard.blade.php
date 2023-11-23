<div
  x-data="{deleteScreen:false,editScreen:false}"
  @mouseleave="deleteScreen=false,editScreen=false"
  class=" md:w-[600px] 2card:w-auto group relative text-start md:text-justify m-2 flex border border-t-4 border-x-gray-900 border-b-gray-900 border-emerald-400 rounded-br-3xl bg-gray-950  hover:border-b-emerald-400 hover:border-x-emerald-400 flex-row">
  <img
  :class="{'blur-sm':deleteScreen || editScreen}"
    class="max-w-[150px] h-[225px] md:w-[200px] object-cover "
    src="https://image.tmdb.org/t/p/w500{{$poster}}"
    alt="{!!$title!!}'s poster"
    onerror="this.src='{{asset('media/placeholder.png')}}'"/>
  <div class="flex justify-items-end flex-col p-6">
    <h5
    :class="{'blur-sm':deleteScreen || editScreen}"
      class=" text-lg font-medium text-neutral-50">
      {!!$title!!}
    </h5>
    <p
    :class="{'blur-sm':deleteScreen || editScreen}"
    class="mb-1 text-base text-neutral-200">
      {!!Str::limit($desc,200)!!}
    </p>
        <span  @click="deleteScreen= !deleteScreen, editScreen=false" class="md:hidden group-hover:block absolute left-2 top-2" style="cursor: pointer;">
            <i class=" fa-solid fa-circle-minus fa-xl" style="color: #34d399;"></i>
        </span>
        <span  @click="editScreen= !editScreen, deleteScreen=false" class="md:hidden group-hover:block absolute left-10 top-2" style="cursor: pointer;">
            <i class="fa-solid fa-gear" style="color: #34d399;"></i>
        </span>

        <a href="{{route('shows.show',$tmdbid)}}" :disabled="deleteScreen||editScreen" :class="{'blur-sm':deleteScreen || editScreen}"
            class="md:absolute md:right-7 md:bottom-2 block md:text-center text-end p-3 md:p-2 text-emerald-400 hover:text-emerald-500 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300"
            style="cursor: pointer;">
            <i class="fa-solid fa-angles-right fa-2x"></i>

        </a>


        <div x-show="deleteScreen" class="navButtonNoHover blur-none absolute border-2 border-emerald-400 border-r-0 bg-gray-900">
            <p>Would you like to delete this show from your favourites?<p>
                <div class="my-2 flex justify-center">
                <form  method="POST" action="{{ route('shows.destroy',$id) }}">
                    @csrf
                    @method('DELETE')
                    <i onclick='this.parentNode.submit();return false;' class="fa-solid fa-circle-check fa-2xl m-5" style="color: #34d399;cursor: pointer;"></i>
                </form>
                <i @click="deleteScreen=!deleteScreen" class="fa-solid fa-circle-xmark fa-2xl m-5" style="color: #34d399; cursor: pointer;"></i>
                </div>
        </div>

        <div x-show="editScreen" class="navButtonNoHover blur-none absolute top-10 right-0 border-2 border-emerald-400 border-r-0 bg-gray-900">
            <p>Do you want to edit this show?</p>
            <div class="my-2 flex justify-center">
                <form  method="GET" action="{{ route('shows.edit',$id) }}">
                    @csrf
                    <i onclick='this.parentNode.submit();return false;' class="fa-solid fa-circle-check fa-2xl m-5" style="color: #34d399;cursor: pointer;"></i>
                </form>
                <i @click="editScreen=!editScreen" class="fa-solid fa-circle-xmark fa-2xl m-5" style="color: #34d399; cursor: pointer;"></i>
                </div>

        </div>

  </div>
</div>
