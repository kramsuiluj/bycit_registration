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
    {{-- ! Modal for the information of the attendee --}}
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
            <p class="text-slate-700 text-sm sm:text-base">Participant Information</p>

            <hr>

            <div id="participant-details" class="text-sm sm:text-base">
                <p id="empty-details" class="text-slate-500 text-sm mb-2"></p>
                <p id="participant-name"></p>
                <p id="participant-size"></p>
                <p id="participant-course"></p>
                <p id="participant-notes"></p>
            </div>

            <div class="flex justify-end space-x-3">
                <button id="confirm-btn" type="button"
                    class="focus:outline-none text-white
                    bg-purple-700
                    hover:bg-purple-800
                    focus:ring-4 focus:ring-purple-300 font-medium text-sm sm:text-base rounded-lg text-sm px-5 py-2.5 mb-2
                    dark:bg-purple-600
                     dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    {{-- ! Form for the day --}}
    <form>
        @csrf
        <input type="hidden" id="day"value="{{ $day }}">
    </form>

    <div class="w-11/12 mx-auto" id="qr">
        <video id="qr-video"></video>
    </div>

    <div id="app"></div>

    <script src="{{ asset('jquery.min.js') }}"></script>

    <script type="module">
        const video = document.getElementById('qr-video');
        const day = document.getElementById('day')

        const modalContainer = document.getElementById('modal-container');
        const modalContent = document.getElementById('modal-content');
        const qr = document.getElementById('qr');

        const participantName= document.getElementById('participant-name');
        const participantSize= document.getElementById('participant-size');
        const participantCourse= document.getElementById('participant-course');
        const participantNote= document.getElementById('participant-notes');

        const confirmBtn = document.getElementById('confirm-btn');
        console.log(`Day: ${day.value}`)
        $.process = function(result){
            var id = result.data
            $.ajax({
                    type: 'get',
                    url: `/admin/registrations/snack/${day.value}/${id}`,
                    
                    success: function(response){
                        var message = response['message'] ?? "Not Found"
                        var fullName = response['full_name'] ?? "Not Found"
                        var course = response['course'] ?? "Not Found"
                        var size = response['tshirt'] ?? "Not Found"
                        if(participantName.innerHTML.length == 0){
                            modalContainer.style.display = 'block';
                            modalContainer.style.position = 'fixed';
                            modalContent.style.display = 'block';
                            var alreadyServed = /already/.test(message)
                            qr.style.display = 'none';
                            participantName.append(`Full Name: ${fullName}`)
                            participantSize.append(`T-shirt Size: ${size}`)
                            participantCourse.append(`Course: ${course}`)
                            participantNote.append(`Note: ${message}`)
                            participantNote.classList.add('font-bold')
                            if(alreadyServed)
                                participantNote.classList.add('text-red-500')
                            confirmBtn.addEventListener('click', ()=>{
                                modalContainer.style.display = 'none';
                                modalContent.style.display = 'none';
                                qr.style.display = 'block'
                                participantName.innerHTML = ''
                                participantSize.innerHTML = ''
                                participantCourse.innerHTML = ''
                                participantNote.innerHTML = ''
                                if(alreadyServed)
                                  participantNote.classList.remove('text-red-500')


                            })
                        }
                
                    }
                    

                }
                )
                
        }
        $(document).ready(function() {
         });

        const scanner = new QrScanner(video, result => $.process(result), {
            highlightScanRegion: true,
            highlightCodeOutline: true,
            maxScansPerSecond: 1,
        });

        scanner.start();
    </script>
</x-layout>
