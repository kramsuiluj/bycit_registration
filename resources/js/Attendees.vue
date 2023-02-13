<template>
    <div class="w-11/12 mx-auto font-montserrat">
        <p ref="message-container" class="absolute top-24 rounded-full px-2 text-sm text-white">
            {{ message }}
        </p>
    </div>

    <div class="w-11/12 mx-auto font-montserrat bg-white mt-2 p-2">
        <ul v-for="attendee in attendees">
            <li>{{ attendee.lastname + ', ' + attendee.firstname }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                attendees: {},
                message: ''
            }
        },
        mounted() {
            this.getAttendance();
        },
        methods: {
            setAttendance(url) {
                 axios.patch(url).then(result => {
                     this.message = result.data.message;
                     this.attendees = result.data.attendees;

                     if (result.data.success === '1') {
                         this.$refs["message-container"].style.display = 'block'
                         this.$refs["message-container"].classList.remove('bg-red-500');
                         this.$refs["message-container"].classList.remove('bg-blue-500');
                         this.$refs["message-container"].classList.add('bg-green-500');

                         setTimeout(() => {
                             this.$refs["message-container"].style.display = 'none'
                         }, 2000)
                     }

                     if (result.data.success === '0') {
                         this.$refs["message-container"].style.display = 'block'
                         this.$refs["message-container"].classList.remove('bg-red-500');
                         this.$refs["message-container"].classList.remove('bg-green-500');
                         this.$refs["message-container"].classList.add('bg-blue-500');

                         setTimeout(() => {
                             this.$refs["message-container"].style.display = 'none'
                         }, 2000)
                     }

                }).catch(error => {
                     this.message = 'QR Code is invalid!';
                     this.$refs["message-container"].style.display = 'block'
                     this.$refs["message-container"].classList.remove('bg-green-500');
                     this.$refs["message-container"].classList.remove('bg-blue-500');
                     this.$refs["message-container"].classList.add('bg-red-500');

                     setTimeout(() => {
                         this.$refs["message-container"].style.display = 'none'
                     }, 2000)
                 });
            },
            getAttendance() {
                axios.get('/api/registrations/attendees').then(result => {
                    this.attendees = result.data.attendees;
                })
            }
        }
    }
</script>

<style>

</style>
