<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <h2>Welcome, Admin!</h2>

    <button id="logoutBtn">Logout</button>

    <script>
        $(document).ready(function() {
            $('#logoutBtn').click(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('logout') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {

                        window.location.href = "{{ route('login') }}";
                    },
                    error: function(xhr) {
                        alert('Logout failed!');
                    }
                });
            });
        });
    </script>
</body>
</html>
