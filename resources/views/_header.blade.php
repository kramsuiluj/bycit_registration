<header class="mb-8 sm:mb-10 w-11/12 mx-auto py-5">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4 justify-start">
            <img src="{{ asset('images/JPCS.png') }}" alt="JPCS Logo" class="w-12 sm:w-14">
            <a href="{{ route('registrations.create') }}" class="font-montserrat text-base
            text-white sm:text-2xl">JPCS - CSPC Chapter</a>
        </div>

        @auth
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-7 h-7 text-white drop-shadow-lg hover:bg-gray-400
                        hover:rounded-full hover:p-2 cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" />
                        </svg>
                    </button>
                </form>
            </div>
        @endauth
    </div>
</header>
