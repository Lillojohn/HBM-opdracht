<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel - Home</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-amber-100 h-100">
<div>
    <h1 class="text-xl">Login om uw takenlijst te zien.</h1>
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
        <form action="{{ route('login') }}" method="POST">
            <div class="mb-4">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="email"
                       type="email" name="email" placeholder="email">
            </div>
            <div class="mb-6">
                <label class="block text-grey-darker text-sm font-bold mb-2" for="password">
                    Wachtwoord
                </label>
                <input
                    class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3"
                    id="password" type="password" name="password" placeholder="******************">
            </div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Login
            </button>
        </form>
    </div>
    <div>
        <h2>Nog niet geregistreerd? Klop op de <a class="font-bold" href="{{route('registration')}}">link om te
                registreren.</a></h2>
    </div>

</div>
</body>
</html>
