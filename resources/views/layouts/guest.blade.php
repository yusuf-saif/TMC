<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'The Muhsinat Club') }}</title>

        <link rel="icon" type="image/png" href="/images/img1.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Dancing+Script:wght@400&family=Nunito:ital,wght@0,300;0,400;0,600;1,300&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
          :root{ --teal:#1A6B72; --teal-dk:#0D3F44; --ivory:#FAF8F3; --gold:#C8A84B; --gold-lt:#E8CB7A; }
          body{ font-family:'Nunito',system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,'Noto Sans','Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol'; background:var(--ivory); color:#1C1A17; }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[var(--ivory)]">
            <div>
                <a href="/" wire:navigate>
                    <img src="/images/img1.png" alt="TMC" class="w-16 h-16 object-contain" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow tmc-shadow rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
