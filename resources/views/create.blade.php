<x-layout>
    <header class="mb-8 sm:mb-12 w-10/12 mx-auto py-5 sm:ml-20">
        <div class="flex items-center space-x-4 justify-start">
            <img src="{{ asset('images/JPCS.png') }}" alt="JPCS Logo" class="w-12 sm:w-14">
            <a href="{{ route('registrations.create') }}" class="font-montserrat text-base
            text-white sm:text-xl">JPCS - CSPC Chapter</a>
        </div>
    </header>

    <h1 class="font-montserrat text-2xl font-bold text-white mb-12 sm:mb-20 flex justify-start w-10/12 mx-auto sm:ml-20
    sm:text-3xl md:text-4xl">BYCIT
        Registration</h1>

    <section class="w-10/12 mx-auto font-montserrat space-y-5 mb-24 sm:w-8/12 md:w-7/12 sm:ml-20">
        <div class="space-y-5 sm:space-y-0 sm:flex sm:space-x-2">
            <input
                type="text"
                id="lastname"
                name="lastname"
                class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5"
                placeholder="Last Name"
                form="register"
                required
            >

            <input
                type="text"
                id="firstname"
                name="firstname"
                class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5"
                placeholder="First Name"
                form="register"
                required
            >

            <input
                type="text"
                id="middleinitial"
                name="middleinitial"
                class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5"
                placeholder="Middle Initial"
                form="register"
            >
        </div>

        <select name="school" id="school" class="w-full p-4 rounded-md text-sm sm:text-base sm:p-4.5" form="register"
                required>
            <option disabled selected>School</option>
            <option value="">Camarines Sur Polytechnic Colleges</option>
            <option value="">Camarines Sur Polytechnic Colleges - Buhi Campus</option>
            <option value="">ACLC College of Iriga</option>
        </select>

        <div class="flex text-white space-x-6">
            <div class="flex items-center space-x-2">
                <input type="radio" name="type" value="student" id="student" class="w-4 h-4 sm:w-5 sm:h-5" checked>
                <label for="student">Student</label>
            </div>

            <div class="flex items-center space-x-2">
                <input type="radio" name="type" value="teacher" id="teacher" class="w-4 h-4 sm:w-5 sm:h-5">
                <label for="teacher">Teacher</label>
            </div>
        </div>
    </section>

    <section class="w-10/12 mx-auto font-montserrat sm:w-8/12 md:w-7/12 sm:ml-20">
        <button id="register-btn" type="button" class="text-white bg-gradient-to-r from-purple-500 to-pink-500
        hover:bg-gradient-to-l
        focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 font-medium rounded-lg
        text-sm px-5 py-4 sm:p-4.5 text-center w-full hover:border-2 hover:border-white">Register
        </button>
    </section>

    <div class="font-montserrat">
        <div id="modal-container" style="display: none;" class="mx-auto w-full h-screen
        center
        bg-gray-900
        opacity-50">
        </div>

        <div id="modal-content" style="display: none;" class="absolut bg-white center px-10 py-5 rounded-md space-y-5
        w-72
        sm:w-96">
            <p class="text-slate-700 text-sm sm:text-base">Confirm Registration ?</p>

            <hr>

            <div id="participant-details" class="text-sm sm:text-base">
                <p id="empty-details" class="text-slate-500 text-sm"></p>
                <p id="participant-name"></p>
                <p id="participant-school"></p>
            </div>

            <div class="flex justify-end space-x-3">
                <button id="confirm-btn" type="submit" form="register" class="focus:outline-none text-white
                bg-purple-700
                hover:bg-purple-800
                focus:ring-4 focus:ring-purple-300 font-medium text-sm sm:text-base rounded-lg text-sm px-5 py-2.5 mb-2
                dark:bg-purple-600
                 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Confirm
                </button>
                <button id="cancel-btn" type="button" class="py-2 px-5 mr-2 mb-2 text-sm font-medium text-gray-900
                focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
                focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800
                dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 text-sm sm:text-base">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <form id="register" action="" method="POST">
        @csrf
    </form>

    <script>
        const registerBtn = document.getElementById('register-btn');
        const confirmBtn = document.getElementById('confirm-btn');
        const cancelBtn = document.getElementById('cancel-btn');

        const modalContainer = document.getElementById('modal-container');
        const modalContent = document.getElementById('modal-content');

        const participantDetails = document.getElementById('participant-details');
        const participantName = document.getElementById('participant-name');
        const participantSchool = document.getElementById('participant-school');
        const emptyDetails = document.getElementById('empty-details');

        const firstname = document.getElementById('firstname') ?? '';
        const middleInitial = document.getElementById('middleinitial') ?? '';
        const lastname = document.getElementById('lastname') ?? '';
        const school = document.getElementById('school') ?? '';

        let type;

        registerBtn.addEventListener('click', () => {
            modalContainer.style.display = 'block';
            modalContent.style.display = 'block';

            if (firstname.value === '' && lastname.value === '' && middleInitial.value === '' && school.value ===
                'School') {
                emptyDetails.append('Please fill in the form.');
            }

            if ((firstname.value === '' || lastname.value === '' || middleInitial.value === '' || school.value ===
                'School') && (firstname.value !== '' || lastname.value !== '' || middleInitial.value !== '' || school
                .value !==
                'School')) {
                emptyDetails.append('Please fill the form completely.');
            }

            participantName.append(
                (lastname.value !== '' ? (lastname.value + ', ') : '') + firstname.value + ' ' +
                middleInitial.value
            );
            participantSchool.append(
                school.value === 'School' ? '' : school.value
            );
        });

        cancelBtn.addEventListener('click', () => {
            modalContainer.style.display = 'none';
            modalContent.style.display = 'none';

            if (firstname.value === '' && lastname.value === '' && middleInitial.value === '' && school.value ===
                'School') {
                emptyDetails.innerText = '';
            }

            if ((firstname.value === '' || lastname.value === '' || middleInitial.value === '' || school.value ===
                'School') && (firstname.value !== '' || lastname.value !== '' || middleInitial.value !== '' || school
                    .value !==
                'School')) {
                emptyDetails.innerText = '';
            }

            participantName.innerHTML = '';
            participantSchool.innerHTML = '';
        });
    </script>
</x-layout>
