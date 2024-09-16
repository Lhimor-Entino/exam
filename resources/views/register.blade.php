<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 2em;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        div {
            margin-bottom: 1em;
        }
        label {
            font-weight: bold;
            margin-bottom: 0.5em;
            display: block;
        }
        input {
            width: 100%;
            padding: 0.8em;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 0.8em;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        #error {
            margin-top: 1em;
            font-size: 0.9em;
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container">
        <h1>Register</h1>
        <form id="registerForm">
            @csrf
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit">Register</button>
            <div id="error" style="color: red; display: none;"></div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(event) {
                event.preventDefault();
                var redirectUrl = "{{ route('thanks') }}";
                $.ajax({
                    url: '{{ route('register.post') }}', // POST route URL
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = redirectUrl; // Redirect on success
                        } else {
                            $('#error').text(response.message).show();
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = 'An error occurred.';
                        if (errors) {
                            errorMessage = Object.values(errors).flat().join(' ');
                        }
                        $('#error').text(errorMessage).show();
                    }
                });
            });
        });
    </script>
</body>
</html>
