<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MyExam</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0B1C33] min-h-screen flex items-center justify-center">

    <div class="w-full flex flex-col items-center">

        {{-- LOGO UNIQUE --}}
        <div class="mb-8">
            <a href="/">
                <img
                    src="{{ asset('images/myexam-logo.png') }}"
                    alt="MyExam"
                    class="h-10 w-auto"
                >
            </a>
        </div>

        {{-- SLOT --}}
        <div class="w-full max-w-md px-6">
            {{ $slot }}
        </div>

    </div>

</body>
</html>
