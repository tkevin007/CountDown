<div x-show="closed" x-data="{closed:true}" class=" w-full md:w-1/2 m-auto mt-0 mb-2 bg-emerald-600 hover:bg-emerald-700 border-2 border-emerald-800 text-white px-3 py-3 rounded relative top-0" role="alert">
    <strong class="font-bold">{{$primaryText}}</strong>
    <span class="block sm:inline">{{$secondaryText ?? "" }}</span>

    @if (($closable??"")!="false")
        <span x-on:click="closed=!closed" class="absolute top-0  right-0 px-4 py-3">
            <i class="fa-solid fa-circle-xmark fa-lg" style=" cursor: pointer;"></i>
        </span>
    @endif
  </div>
