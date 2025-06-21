<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reverb Test</title>
</head>
<body>
    <h1>Testing Laravel Reverb Real-Time Event</h1>
    <div id="message">Waiting for broadcast...</div>

    <!-- Include your compiled app.js if needed -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // JavaScript to set up Echo and listen for the Reverb event
        window.Echo.channel('test-channel')
            .listen('.test-event', (e) => {
                console.log(e.message);
                document.getElementById('message').innerText = "Message received: " + e.message;
            });
    </script>
</body>
</html>
