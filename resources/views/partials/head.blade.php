<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<<<<<<< HEAD
<link rel="icon" type="image/jpeg" href="{{ asset('images/livecafelogo.jpeg') }}">
=======
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
>>>>>>> 15ff7d93549fad61a193a6918816624dddfac3b3

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
