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
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col">
        @if (Session::get('status'))
            <div class="alert font-bold text-xl bg-white-100">
                {{ Session::get('status') }}
            </div>
        @endif
        <!-- Render Task Lists -->
        @foreach ($taskLists as $taskList)
            <div class="bg-red-100 w-64">
                <!-- Render name of Task List -->
                <h2 class="text-xl font-bold">{{ $taskList->name }} Lijst</h2>
                <!-- Render description of Task List -->
                <p>{{ $taskList->description }}</p>
                <!-- Render tasks of Task List -->
                <form action="{{route('searchTasks')}}"  method="post">
                    @csrf
                    <input type="text" name="search" placeholder="Zoek op taaknaam">
                    <button class="block w-50 bg-blue-100" type="submit">Zoeken</button>
                </form>
                @if ($search)
                    <p>Resultaten voor: {{$search}}</p>
                    <a href="{{route('tasks')}}"><button class="w-50 bg-purple-100" type="submit">Terug</button></a>
                @endif
                @foreach ($taskList->searchTasks($search)->paginate(10) as $task)
                    <div class="bg-green-100 border-2 border-dashed">
                        <a href="{{ route('getTask', ['id' => $task->id]) }}"><h3
                                class="text-lg font-bold">{{ $task->name }} Taak</h3></a>
                        <p>{{ $task->description }}</p>
                        <a href="{{route('updatePage', ['id'=> $task->id])}}" ><button class="w-50 bg-purple-100" type="submit">Aanpassen</button></a>
                        <!-- Render form to delete a task -->
                        <form action="{{ route('deleteTask') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $task->id }}">
                            <button class="w-50 bg-purple-100" type="submit">Verwijderen</button>
                        </form>
                    </div>
                @endforeach
                <!-- Render form to create a new task -->
                <form action="{{ route('createTask') }}" method="POST">
                    @csrf
                    <h2 class="text-xl font-bold">Nieuwe taak</h2>
                    <input type="hidden" name="taskListId" value="{{ $taskList->id }}">
                    <input type="text" name="name" placeholder="Taaknaam">
                    <input type="text" name="description" placeholder="Taakbeschrijving">
                    <button class="block w-50 bg-blue-100" type="submit">Taak toevoegen</button>
                </form>
                <!-- Render pagination links -->
                {{ $taskList->searchTasks($search)->paginate(10)->links() }}
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
