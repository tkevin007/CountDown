<x-head>
</x-head>

<x-navbar>
</x-navbar>

<div class=" text-white">
    <div class="grid w-full grid-flow-row grid-cols-1 justify-items-center m-auto gap-2">
        <div
            class=" relative 2card:max-w-[1200px] group text-start md:text-justify m-2  border border-t-4 border-x-gray-900 border-b-gray-900 border-emerald-400 rounded-br-3xl bg-gray-950  hover:border-b-emerald-400 hover:border-x-emerald-400">
            <div class=" pr-3">

                <div class="m-0">
                    <form method="POST" class="navButtonNoHover mt-5 p-0 pl-5 md:text-start text-center" name="form1"
                        id="form1" action="{{ route('report.store') }}">
                        @csrf
                        @method('POST')

                        <div class="flex flex-row">
                            <img class=" w-[200px] md:w-[250px] float-left p-0 mr-5 mb-5"
                                src="{{ asset('media/logo_xl.png') }}" alt="CountDown logo" />

                            <div class="flex flex-col">
                                <h5 class="place-self-center w-56 text-center text-lg font-medium navButtonNoHover p-0">
                                    Make a <b>Bug report</b> or <b>send a message</b> to the admins
                                </h5>

                                <label class=" flex text-sm md:text-lg navButtonNoHover" for="type">Message
                                    Type:</label>
                                <select class=" md:w-56 w-full text-emerald-400 bg-gray-800" name="type"
                                    id="type">
                                    <option value="Report" selected>Report</option>
                                    <option value="Message">Message</option>
                                </select>
                            </div>
                        </div>
                            <div class="mb-2">
                                <label class="flex text-sm md:text-lg navButtonNoHover" for="message">Message:</label>
                                <textarea class="m-auto w-full min-h-[150px] text-emerald-400 bg-gray-800" value="" name="message" id="message"></textarea>
                            </div>
                        <div class="text-end pb-2">
                            <input
                                class=" hover:bg-gray-800 rounded-xl p-2 border-2 border-emerald-400 w-full md:w-60 disabled "
                                style="cursor:pointer" type="submit" value="Send">
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<x-footer>
</x-footer>
