<x-layout>
    <div class="center font-montserrat">

        <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
            <h2 class="mb-5">Are you the administrator?</h2>
            <form class="space-y-6" action="{{ route('admin.login') }}" method="POST">
                @csrf

                <div>
                    <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300
                    text-gray-900
                    text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600
                    dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="Username" required>

                    @error('username')
                    <p class="text-red-500 text-sm drop-shadow-md">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>

                    @error('password')
                    <p class="text-red-500 text-sm drop-shadow-md">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4
                focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">LOG IN</button>
            </form>
        </div>

    </div>
</x-layout>
