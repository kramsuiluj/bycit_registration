<x-layout>
    <header class="mb-8 sm:mb-12 w-11/12 mx-auto py-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 justify-start">
                <img src="{{ asset('images/JPCS.png') }}" alt="JPCS Logo" class="w-12 sm:w-14">
                <a href="{{ route('registrations.create') }}" class="font-montserrat text-base
            text-white sm:text-2xl">JPCS - CSPC Chapter</a>
            </div>

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
        </div>
    </header>

    <div class="w-11/12 mx-auto font-montserrat pb-5 flex justify-between items-end">
        <div class="flex items-end space-x-1">
            <form action="" class="flex items-end space-x-1">
                <div class="flex flex-col space-y-1">
                    <label class="text-white drop-shadow-md text-sm" for="school">College</label>
                    <select name="school" id="">
                        <option value="">All</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ $school->id == request('school') ? 'selected' : '' }}>{{
                        $school->name
                        }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col space-y-1">
                    <label class="text-white drop-shadow-md text-sm" for="school">Confirmed</label>
                    <select name="confirmed" id="">
                        <option value="">All</option>
                        <option value="yes" {{ request('confirmed') == 'yes' ? 'selected' : '' }}>Confirmed</option>
                        <option value="no" {{ request('confirmed') == 'no' ? 'selected' : '' }}>Unconfirmed</option>
                    </select>
                </div>

                <div class="flex flex-col space-y-1">
                    <label class="text-white drop-shadow-md text-sm" for="type">Type</label>
                    <select name="type" id="">
                        <option value="">All</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="text-white bg-purple-700 hover:bg-purple-800 focus:outline-none focus:ring-4
             focus:ring-purple-300 font-medium text-sm px-5 py-2.5 text-center dark:bg-purple-600
             dark:hover:bg-purple-700 dark:focus:ring-purple-900 border">
                        Filter
                    </button>
                </div>
            </form>

            <form action="{{ route('registrations.export') }}"
                  method="POST"
            >
                @csrf

                <input type="hidden" name="school" value="{{ request('school') }}">
                <input type="hidden" name="confirmed" value="{{ request('confirmed') }}">

                <button type="submit" class="text-white
                bg-green-700
                hover:bg-green-800
                focus:outline-none
                focus:ring-4
             focus:ring-green-300 font-medium border text-sm px-5 py-2.5 text-center dark:bg-green-600
             dark:hover:bg-green-700 dark:focus:ring-green-900">Export Record</button>
            </form>
        </div>

        <p class="text-white drop-shadow-lg text-lg">Participants: <span class="font-bold">{{ $totalRegistrations
        }}</span></p>
    </div>

    <section class="w-11/12 mx-auto font-montserrat">
        @if(count($registrations) !== 0)
            <div class="flex flex-col">
                <div class="">
{{--                    overflow-x-auto sm:-mx-6 lg:-mx-8--}}
                    <div class="py-2 inline-block min-w-full ">
{{--                        sm:px-6 lg:px-8--}}
                        <div class="overflow-hidden">
                            <table class="min-w-full border text-center bg-white">
                                <thead class="border-b">
                                <tr>
                                    <th scope="col" class="text-sm font-medium text-gray-900  py-4 border-r">
                                        Full Name
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900  py-4 border-r">
                                        Type
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900  py-4 border-r">
                                        College
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900  py-4 border-r">
                                        Date Registered
                                    </th>
                                    <th scope="col" class="text-sm font-medium text-gray-900  py-4 border-r">
                                        Confirmation
                                    </th>
                                    <th colspan="2" class="text-sm font-medium text-gray-900  py-4 border-r">
                                        Actions
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($registrations as $registration)
                                    <tr class="border-b hover:bg-gray-100">
                                        <td class="text-left pl-4 py-3 whitespace-nowrap text-sm font-medium
                                        text-gray-900
                                        border-r">
                                            {{ $registration->fullname() }}
                                        </td>
                                        @if($registration->isStudent())
                                            <td class="text-sm text-gray-900 py-3 whitespace-nowrap border-r
                                            bg-blue-400
                                            text-white">
                                                <span class="px-1">{{ $registration->type }}</span>
                                            </td>
                                        @else
                                        <td class="text-sm text-gray-900 font-light  py-3 whitespace-nowrap border-r
                                        bg-purple-400 text-white">
                                                <span class="px-1">{{ $registration->type }}</span>
                                            </td>
                                        @endif
                                        <td class="text-left  pl-4 text-sm text-gray-900 py-3 whitespace-nowrap
                                        border-r">
                                            {{ $registration->school->name }}
                                        </td>
                                        <td class="text-sm text-gray-900 font-light  py-3 whitespace-nowrap border-r">
                                            {{ $registration->date_registered }}
                                        </td>

                                        @if($registration->confirmed === 'yes')
                                            <td class="text-sm text-gray-900  py-3 whitespace-nowrap border-r
                                            bg-green-400 text-white">
                                                <span class="">Confirmed</span>
                                            </td>
                                        @endif

                                        @if($registration->confirmed === 'no')
                                            <td class="text-sm text-gray-900  py-3 whitespace-nowrap border-r
                                            bg-gray-400
                                             text-white">
                                                Unconfirmed
                                            </td>
                                        @endif

                                        @if($registration->confirmed())
                                            <td class="text-sm text-gray-900 font-light whitespace-nowrap
                                            bg-gray-500 px-2 cursor-pointer text-white hover:bg-gray-600" id="{{ $registration->id }}" onclick="updateConfirmation(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     fill="none" viewBox="0 0 24
                                                    24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </td>
                                        @else
                                            <td class="text-sm text-gray-900 font-light whitespace-nowrap
                                            bg-green-500 px-2 cursor-pointer text-white hover:bg-green-600" id="{{ $registration->id }}" onclick="updateConfirmation(this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24
                                                    24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </td>
                                        @endif
                                        <td class="text-sm text-gray-900 font-light whitespace-nowrap bg-red-500
                                        text-white
                                        px-2
                                        cursor-pointer
                                        hover:bg-red-600
                                        " data-modal-target="popup-modal" data-modal-toggle="popup-modal"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mx-auto
                                                ">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </td>
                                    </tr>

                                    <div id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden
    overflow-y-auto md:inset-0 h-modal md:h-full font-montserrat">
                                        <div class="relative w-full h-full max-w-md md:h-auto">
                                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="popup-modal">
                                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                                <div class="p-6 text-center">
                                                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    <h3 class="mb-5 text-lg font-normal text-gray-500
                                                            dark:text-gray-400 whitespace-normal">Are you sure you want to
                                                        delete this participant?</h3>
                                                    <button id="{{ $registration->id }}"
                                                            data-modal-hide="popup-modal"
                                                            type="submit"
                                                            onclick="deleteParticipant(this)" class="text-white
                                                                    bg-red-600
                                                                    hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                        Yes, I'm sure
                                                    </button>
                                                    <button data-modal-hide="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <form
                                        id="delete-form-{{ $registration->id }}"
                                        action="{{ route('registrations.destroy', $registration->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <form
                                        id="update-form-{{ $registration->id }}"
                                        action="{{ route('registrations.update', $registration->id) }}"
                                        method="POST"
                                        style="display: none"
                                    >
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="text-white">There are no participant records.</p>
        @endif
    </section>

    <div class="w-11/12 mx-auto mb-5 font-montserrat pb-5">
        {{ $registrations->links() }}
    </div>

    @if(session()->has('success'))
        <div class="mx-auto absolute bottom-0 right-0 mb-5 mr-5 font-montserrat">
            <p
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 4000)"
                x-show="show"
                class="bg-blue-500 text-white py-2 px-4 rounded-xl text-base flex justify-center text-center
                items-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>

                <span>{{ session('success') }}</span>
            </p>
        </div>
    @endif

    <script>

        function updateConfirmation(element) {
            let formID = 'update-form-' + element.id;
            document.getElementById(formID).submit();
        }

        function deleteParticipant(element) {
            let formID = 'delete-form-' + element.id;
            document.getElementById(formID).submit();
        }


    </script>
</x-layout>
