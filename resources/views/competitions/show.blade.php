<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <title>Мероприятие: {{ $competition->name }}</title>
    @vite("resources/css/app.css")
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class='logo'>
                @php
                    $dashboardaddress = '';
                    if(auth()->user()->role=='judge') {
                        $dashboardaddress = "../../../judge/dashboard";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../../../media/logoold.png' alt='IT-Куб'>");
                        echo("</a>");
                    }
                @endphp
            </li>
            <li class='username'>
                <p>{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="outtaherebtn">
                        <img src = "../../../media/exit.jpg" alt = "Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="scoreeditor">


        <div class="minidirections">
            <h4><a href="../../../judge/dashboard">← Назад к сканированию</a></h4>
        </div>

        <div class="eventandteams">
            <h1>Мероприятие: {{ $competition->name }}</h1>
            <h2>Команда: {{ $team->name }}</h2>
        </div>


        <div class="competition-list">
            <h3>Мероприятия команды:</h3>
            <ul>
                @foreach ($competitions as $comp)
                    <li>
                        <a href="{{ route('competitions.show', ['competition' => $comp->id, 'team' => $team->id]) }}"
                           class="{{ $comp->id == $competition->id ? 'active' : '' }}">
                            {{ $comp->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        

        <!-- Форма для изменения баллов -->
        <form action="{{ route('scores.update', ['competition' => $competition->id, 'team' => $team->id]) }}" method="POST">
            @csrf
            @method('PUT')
        

            <div class="scoreupdatetable">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Испытание</th>
                            <th>Баллы</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($challenges as $challenge)
                            <tr>
                                <td>{{ $challenge->title }}</td>
                                <td>
                                    <input type="number" class ='pointvalue' name="scores[{{ $challenge->id }}]" 
                                           value="{{ $scores[$challenge->id]->score ?? 0 }}" 
                                           min="0" required>
                            
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            
                <button type="submit" class="btn cubebtn">Сохранить изменения →</button>
            </div>
            
        </form>
    </div>
</body>
</html>