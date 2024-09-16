<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body >
<div class="container" style="width: 40%; margin: 0 auto; padding: 2em; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h1 style="text-align: center; margin-bottom: 1em;">Contact</h1>
    <form id="contactForm" style="display: flex; flex-direction: column; gap: 1em;">
        @csrf
        <div style="display: flex; flex-direction: column;">
            <label for="name" style="margin-bottom: 0.5em; font-weight: bold;">Name:</label>
            <input type="text" id="name" name="name" style="padding: 0.5em; border: 1px solid #ccc; border-radius: 4px; font-size: 1em;" required>
        </div>
        <div style="display: flex; flex-direction: column;">
            <label for="company" style="margin-bottom: 0.5em; font-weight: bold;">Company:</label>
            <input type="text" id="company" name="company" style="padding: 0.5em; border: 1px solid #ccc; border-radius: 4px; font-size: 1em;">
        </div>
        <div style="display: flex; flex-direction: column;">
            <label for="phone" style="margin-bottom: 0.5em; font-weight: bold;">Phone:</label>
            <input type="text" id="phone" name="phone" style="padding: 0.5em; border: 1px solid #ccc; border-radius: 4px; font-size: 1em;" required>
        </div>
        <div style="display: flex; flex-direction: column;">
            <label for="email" style="margin-bottom: 0.5em; font-weight: bold;">Email:</label>
            <input type="email" id="email" name="email" style="padding: 0.5em; border: 1px solid #ccc; border-radius: 4px; font-size: 1em;" required>
        </div>
        <button type="submit" style="padding: 0.75em; border: none; border-radius: 4px; background-color: #007bff; color: white; font-size: 1em; cursor: pointer; transition: background-color 0.3s ease; margin-top: 1em;">
            Add Contact
        </button>
        <div id="responseMessage" style="color: green; display: none; margin-top: 1em;"></div>
        <div id="errorMessage" style="color: red; display: none; margin-top: 1em;"></div>
    </form>
</div>


    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(event) {
                event.preventDefault();
                var redirectUrl = "{{  route('contacts.index') }}";
                $.ajax({
                    url: '{{ route('contact.store') }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = redirectUrl; // Redirect on success
                            $('#responseMessage').text(response.message).show();
                            $('#contactForm')[0].reset(); // Reset the form fields
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = 'An error occurred.';
                        if (errors) {
                            errorMessage = Object.values(errors).flat().join(' ');
                        }
                        $('#errorMessage').text(errorMessage).show();
                    }
                });
            });
        });
    </script>
</body>
</html>
