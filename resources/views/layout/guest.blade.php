<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ $title ?? 'Guest' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="favicon.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap">
        @vite(['resources/css/style.css', 'resources/js/app.js'])
    </head>

    <body class="font-nunito text-sm antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
            <main>
                {{ $slot }}
            </main>
        </div>

        @vite(['resources/js/custom.js'])
    </body>
</html>
