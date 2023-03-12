<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HBM - Takenlijst</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-amber-100 h-100">
<div>
    <h1 class="text-xl">Takenlijst</h1>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">

        <form action="{{ route('updateTask') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $task->id }}">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="name">Taaknaam</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" type="text"
                   name="name" placeholder="Taaknaam" value="{{ $task->name }}">
            <label class="block text-grey-darker text-sm font-bold mb-2" for="description">Taakbeschrijving</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" type="text"
                   name="description" placeholder="Taakbeschrijving" value="{{ $task->description }}">
            <button class="block w-50 bg-blue-100" type="submit">Taak wijzigen</button>
        </form>
    </div>


</div>
</div>
</body>
</html>
