<x-layout>
    <header class="mb-8 sm:mb-10 w-11/12 mx-auto py-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 justify-start">
                <img src="{{ asset('images/JPCS.png') }}" alt="JPCS Logo" class="w-12 sm:w-14">
                <a href="{{ route('registrations.create') }}"
                    class="font-montserrat text-base
            text-white sm:text-2xl">JPCS - CSPC Chapter</a>
            </div>

            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="w-7 h-7 text-white drop-shadow-lg hover:bg-gray-400
                        hover:rounded-full hover:p-2 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>
   <div class="flex flex-col items-center">
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.firstDay')}}">First Day Registration</a> <br />
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.secondDay')}}">Second Day Registration</a><br />
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.first_snack_am')}}">First Day Snack AM</a><br />
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.first_snack_pm')}}">First Day Snack PM</a><br />
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.second_snack_am')}}">Second Day Snack AM</a><br />
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.second_snack_pm')}}">Second Day Snack PM</a><br />
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.first_lunch')}}">First Day Lunch</a><br />
        <a class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded" href="{{route('registrations.second_lunch')}}">Second Day Lunch</a><br />
        
   </div>
</x-layout>
