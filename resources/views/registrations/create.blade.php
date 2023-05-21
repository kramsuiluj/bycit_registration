<x-layout>
    <header class="mb-8 sm:mb-12 w-10/12 mx-auto py-5 sm:ml-20">
        <div class="flex items-center space-x-4 justify-start">
            <img src="{{ asset('images/JPCS.png') }}" alt="JPCS Logo" class="w-12 sm:w-14">
            <a href="{{ route('registrations.create') }}"
                class="font-montserrat text-base
           text-white sm:text-2xl">JPCS - CSPC Chapter</a>
        </div>
    </header>
    <h1
        class="font-montserrat text-2xl font-bold text-white mb-12 sm:mb-20 flex justify-start w-10/12 mx-auto sm:ml-20
   sm:text-3xl md:text-4xl drop-shadow-2xl">
        BYCIT Registration
    </h1>
    <div class="w-full">
        <img src="{{ asset('images/whiteLogo.png') }}" alt=""
            class="w-1/2 mx-auto sm:w-1/4 sm:mx-0 sm:ml-24 md:w-1/5" id="whiteLogo">
    </div>
    @if (session()->has('success'))
        <div class="w-10/12 mx-auto font-montserrat space-y-5 mb-5 sm:w-3/5 sm:ml-20 shadow-md rounded-sm">
            <p x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                class="bg-green-500 text-white py-2 sm:py-3 px-4 rounded-xl text-sm sm:text-base flex justify-center
           text-center
               items-center space-x-2 drop-shadow-lg"
                style="font-weight: 600">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3
           .org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Registration Successful!</span>
            </p>
        </div>
    @endif
    @if ($errors->has('firstname') && $errors->has('lastname') && $errors->has('middle_initial') && $errors->has('tshirt'))
        @if (str_contains($errors->default->first('tshirt'), 'taken'))
            <div class="w-10/12 mx-auto py-2">
                <p class="text-red-300">You are already registered.
                    {{ old('lastname') . ', ' . old('firstname') . ' ' . old('middle_initial') }}</p>
            </div>
        @endif
    @endif
    <section class="w-10/12 mx-auto font-montserrat space-y-5 mb-24 sm:w-3/5 sm:ml-20">
        <div>
            <div class="flex text-white space-x-6">
                <div class="flex items-center space-x-2">
                    <input onclick="getType(this)" form="register" type="radio" name="type" value="Student"
                        id="student" class="w-4
                   h-4 sm:w-5
                   sm:h-5" checked>
                    <label for="student">Student</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input onclick="getType(this)" form="register" type="radio" name="type" value="Teacher"
                        id="teacher" class="w-4 h-4 sm:w-5
                   sm:h-5">
                    <label for="teacher">Teacher</label>
                </div>
            </div>
            @error('type')
                <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-5 sm:space-y-0 sm:flex sm:space-x-2">
            <div class="w-full">
                <input type="text" id="lastname" name="lastname"
                    class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5
                   @error('lastname') border-2 border-red-400 @enderror
                   "
                    placeholder="Last Name" form="register" value="{{ old('lastname') }}" required>
                @error('lastname')
                    <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                @enderror
            </div>
            <div class="w-full">
                <input type="text" id="firstname" name="firstname"
                    class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5
                   @error('firstname') border-2 border-red-400 @enderror
                   "
                    placeholder="First Name" form="register" value="{{ old('firstname') }}" required>
                @error('firstname')
                    <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                @enderror
            </div>
            <div class="w-full">
                <input type="text" id="middleinitial" name="middle_initial"
                    class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5
                   @error('middle_initial') border-2 border-red-300 @enderror
                   "
                    placeholder="Middle Initial" value="{{ old('middle_initial') }}" form="register">
                @error('middle_initial')
                    <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="space-y-5">
            <select name="school" id="school" onchange="getSchool(this)"
                class="w-full p-4 rounded-md text-sm sm:text-base sm:p-4.5
                   @error('school') border-2 border-red-300 @enderror
                   "
                form="register" required>
                <option disabled selected>School</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" {{ (int) old('school') === $school->id ? 'selected' : '' }}>
                        {{ $school->name }}
                    </option>
                @endforeach
            </select>
            <div class="" id="others-container" style="display: none">
                <div class="w-full">
                    <select name="course" id="course"
                        class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base
                   sm:p-4.5
                   @error('course') border-2 border-red-400 @enderror
                   "
                        form="register">
                        <option value="" disabled selected>Course</option>
                        <option value="BSIT" {{ old('course') === 'BSIT' ? 'selected' : '' }}>BSIT</option>
                        <option value="BSCS" {{ old('course') === 'BSCS' ? 'selected' : '' }}>BSCS</option>
                        <option value="BSIS" {{ old('course') === 'BSIS' ? 'selected' : '' }}>BSIS</option>
                        <option value="BLIS" {{ old('course') === 'BLIS' ? 'selected' : '' }}>BLIS</option>
                    </select>
                    @error('course')
                        <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full">
                    <select name="year" id="year"
                        class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base
                   sm:p-4.5
                   @error('year') border-2 border-red-400 @enderror
                   "
                        form="register">
                        <option value="" disabled selected>Year</option>
                        <option value="1" {{ old('year') === '1' ? 'selected' : '' }}>1st</option>
                        <option value="2" {{ old('year') === '2' ? 'selected' : '' }}>2nd</option>
                        <option value="3" {{ old('year') === '3' ? 'selected' : '' }}>3rd</option>
                        <option value="4" {{ old('year') === '4' ? 'selected' : '' }}>4th</option>
                    </select>
                    @error('year')
                        <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full">
                    <input type="text" id="section" name="section"
                        class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5
                   @error('section') border-2 border-red-400 @enderror
                   "
                        placeholder="Section" form="register" value="{{ old('section') }}">
                    @error('section')
                        <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="space-y-5 sm:flex sm:space-y-0 sm:space-x-2">
                <div class="w-full">
                    <select onchange="getSize(this)" name="tshirt" id="size"
                        class="w-full p-4 rounded-md text-sm sm:text-base sm:p-4.5
   @error('tshirt') border-2 border-red-300 @enderror
   "
                        form="register" required>
                        <option disabled selected>T-shirt size</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}" {{ old('tshirt') == $size->id ? 'selected' : '' }}>
                                {{ $size->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tshirt')
                        <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full">
                    <input type="text" id="nickname" name="nickname"
                        class="w-full p-4 rounded-md bg-gray-100 text-sm sm:text-base sm:p-4.5
                   @error('nickname') border-2 border-red-400 @enderror
                   "
                        placeholder="Nickname" form="register" value="{{ old('nickname') }}">
                    @error('nickname')
                        <p class="text-red-300 text-sm drop-shadow-md">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </section>
    <section class="w-10/12 mx-auto font-montserrat sm:w-3/5 sm:ml-20 -mt-10 mb-5">
        <button id="register-btn" type="button"
            class="text-white bg-gradient-to-r from-purple-500 to-pink-500
       hover:bg-gradient-to-l
       focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 font-medium rounded-lg
       text-sm px-5 py-4 sm:py-4.5 text-center w-full hover:border-2 hover:border-white">Register
        </button>
    </section>
    <div class="font-montserrat">
        <div id="modal-container" style="display: none;"
            class="mx-auto w-full h-screen
       center
       bg-gray-900
       opacity-50">
        </div>
        <div id="modal-content" style="display: none;"
            class="absolute bg-white center px-5 py-5 rounded-md space-y-5
       w-80 sm:w-128">
            <p class="text-slate-700 text-sm sm:text-base">Confirm Registration?</p>
            <hr>
            <div id="participant-details" class="text-sm sm:text-base">
                <p id="empty-details" class="text-slate-500 text-sm mb-2"></p>
                <p id="participant-name"></p>
                <p id="participant-nickname"></p>
                <p id="participant-type"></p>
                <p id="participant-school"></p>
                <p id="participant-size"></p>
                <p id="participant-course"></p>
                <p id="participant-year"></p>
                <p id="participant-section"></p>
            </div>
            <div class="flex justify-end space-x-3">
                <button id="confirm-btn" type="submit" form="register"
                    class="focus:outline-none text-white
               bg-purple-700
               hover:bg-purple-800
               focus:ring-4 focus:ring-purple-300 font-medium text-sm sm:text-base rounded-lg text-sm px-5 py-2.5 mb-2
               dark:bg-purple-600
                dark:hover:bg-purple-700 dark:focus:ring-purple-900">Confirm
                </button>
                <button id="cancel-btn" type="button"
                    class="py-2 px-5 mr-2 mb-2 text-sm font-medium text-gray-900
               focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700
               focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800
               dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 text-sm sm:text-base">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <form id="register" action="{{ route('registrations.store') }}" method="POST">
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
        const participantType = document.getElementById('participant-type');
        const participantSize = document.getElementById('participant-size');
        const participantNickname = document.getElementById('participant-nickname');
        const participantCourse = document.getElementById('participant-course');
        const participantYear = document.getElementById('participant-year');
        const participantSection = document.getElementById('participant-section');
        const emptyDetails = document.getElementById('empty-details');
        const firstname = document.getElementById('firstname') ?? '';
        const middleInitial = document.getElementById('middleinitial') ?? '';
        const lastname = document.getElementById('lastname') ?? '';
        const school = document.getElementById('school') ?? '';
        const nickname = document.getElementById('nickname') ?? '';
        const othersContainer = document.getElementById('others-container');
        const course = document.getElementById('course');
        const year = document.getElementById('year');
        const section = document.getElementById('section');
        let type = 'Student';
        let size = "{{ old('tshirt') ? $sizes->where('id', '=', old('tshirt'))->first()->name : '' }}";

        function getType(element) {
            type = element.value;
            if (school.value === '1' || school.value === '2') {
                if (type !== 'Teacher') {
                    if (window.innerWidth < 576) {
                        othersContainer.style.display = 'block';
                    } else {
                        othersContainer.style.display = 'flex';
                    }
                    course.required = true;
                    year.required = true;
                    section.required = true;
                } else {
                    othersContainer.style.display = 'none';
                    console.log(course.required);
                    course.required = false;
                    year.required = false;
                    section.required = false;
                }
            } else {
                othersContainer.style.display = 'none';
                console.log(course.required);
                course.required = false;
                year.required = false;
                section.required = false;
            }
        }

        function getSize(element) {
            return size = element.options[element.selectedIndex].text;
        }
        // console.log(document.getElementById('size').options[document.getElementById('size').selectedIndex].text);
        String.prototype.toTitleCase = function() {
            return this.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }
        window.onresize = () => {
            if (othersContainer.style.display !== 'none') {
                if (window.innerWidth < 576) {
                    othersContainer.style.display = 'block';
                } else {
                    othersContainer.style.display = 'flex';
                }
            }
        };
        window.onload = () => {
            // function getSize(element) {
            //     return size = element.options[element.selectedIndex].text;
            // }
            // console.log(getSize(document.getElementById('size')));
            if (school.value === '1' || school.value === '2') {
                if (window.innerWidth < 576) {
                    othersContainer.style.display = 'block';
                } else {
                    othersContainer.style.display = 'flex';
                }
            }
        }

        function getSchool(element) {
            // let selectedSchool = element.options[element.selectedIndex].text;
            // console.log(typeof element.value);
            if (element.value === '1' || element.value === '2') {
                if (type !== 'Teacher') {
                    if (window.innerWidth < 576) {
                        othersContainer.style.display = 'block';
                    } else {
                        othersContainer.style.display = 'flex';
                    }
                    course.required = true;
                    year.required = true;
                    section.required = true;
                }
            } else {
                othersContainer.style.display = 'none';
                console.log(course.required);
                course.required = false;
                year.required = false;
                section.required = false;
            }
        }
        confirmBtn.addEventListener('click', () => {
            console.log(course.required);
        })
        registerBtn.addEventListener('click', () => {
            modalContainer.style.display = 'block';
            modalContainer.style.position = 'fixed';
            modalContent.style.display = 'block';
            if (firstname.value === '' && lastname.value === '' && middleInitial.value === '' && school.value ===
                'School') {
                emptyDetails.append('Please fill in the form.');
            }
            if ((firstname.value === '' || lastname.value === '' || middleInitial.value === '' || school.value ===
                    'School') && (firstname.value !== '' || lastname.value !== '' || middleInitial.value !== '' ||
                    school
                    .value !==
                    'School')) {
                emptyDetails.append('Please fill the form completely.');
            }
            fullName = (lastname.value !== '' ? (lastname.value + ', ') : '') + firstname.value + ' ' +
                middleInitial.value + (middleInitial.value !== '' ? '.' : '');
            participantName.append(
                "Full Name: " + (fullName === ' ' ? 'Empty' : fullName.toTitleCase())
            );
            participantNickname.append('Nickname: ' + (nickname.value === '' ? 'Empty' :
                nickname.value.toTitleCase()));
            schoolName = (school.value === 'School' ? '' : school.options[school.selectedIndex].text);
            participantType.append("Type: " + type)
            participantSchool.append("School: " + (schoolName == '' ? 'Empty' : schoolName));
            participantSize.append("Size: " + (size === '' ? 'Empty' : getSize(document.getElementById('size')) ??
                size));
            if (course.required == true && year.required == true && section.required == true) {
                participantCourse.append("Course: " + (course.value === '' ? 'Empty' : course.value));
                participantYear.append("Year: " + (year.value === '' ? 'Empty' : year.value));
                participantSection.append("Section: " + (section.value === '' ? 'Empty' : section.value));
            }
        });
        cancelBtn.addEventListener('click', () => {
            modalContainer.style.display = 'none';
            modalContent.style.display = 'none';

            if (firstname.value === '' && lastname.value === '' && middleInitial.value === '' && school.value ===
                'School') {
                emptyDetails.innerText = '';
            }

            if ((firstname.value === '' || lastname.value === '' || middleInitial.value === '' || school.value ===
                    'School') && (firstname.value !== '' || lastname.value !== '' || middleInitial.value !== '' ||
                    school
                    .value !==
                    'School')) {
                emptyDetails.innerText = '';
            }
            participantType.innerHTML = '';
            participantSize.innerHTML = '';
            participantName.innerHTML = '';
            participantSchool.innerHTML = '';
            participantNickname.innerHTML = '';
            participantCourse.innerHTML = '';
            participantYear.innerHTML = '';
            participantSection.innerHTML = '';
        });
    </script>
</x-layout>


{{-- <x-layout>
    <div style='position:relative; padding-bottom:calc(65.66% + 44px)'><iframe src='https://gfycat.com/ifr/DiligentSelfassuredArthropods' frameborder='0' scrolling='no' width='100%' height='100%' style='position:absolute;top:0;left:0;' allowfullscreen></iframe></div><p> <a href="https://gfycat.com/diligentselfassuredarthropods-surprise-pikachu-infinite-meme">via Gfycat</a></p>
</x-layout> --}}
