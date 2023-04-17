<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test</title>
</head>
<body>
    <form id="hidden" name="hidden">
        <input type="text" id="test" name="test" placeholder="Hello">
        <input type="submit">
    </form>
    <p id="result" name="result"></p>

    <script src="{{asset('jquery.min.js')}}"></script>
    <script>
        $(function(){
            $("#hidden").on('submit', function(e){
                var id = 2

                $.ajax({
                    type: 'get',
                    url: '/admin/registrations/attendance/second/' + id,
                    success: function(response){
                        console.log(response)
                    }

                })
                $('#result').text($('#test').val())
                e.preventDefault()
            });
        })
    </script>
</body>
</html>