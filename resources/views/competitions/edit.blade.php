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
    <title>Редактирование мероприятия | Система проведения мероприятий IT-Куб.Курган</title>
    @vite("resources/css/app.css")
    @vite("resources/js/app.js")
    @vite("resources/js/trashButton.js")
    @vite("resources/js/teamTrashButton.js")
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class="logo">
                @php
                    $dashboardaddress = '';
                    if(auth()->user()->role == 'admin') {
                        $dashboardaddress = "../../admin/dashboard";
                        echo("<a href='$dashboardaddress'>");
                        echo("<img src='../../media/logoold.png' alt='IT-Куб'>");
                        echo("</a>");
                    }
                @endphp
            </li>
            <li class="username">
                <p>{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="outtaherebtn">
                        <img src="../../media/exit.jpg" alt="Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="basicname">
        <div class="minidirections">
            <h4><a href="../../admin/dashboard"> Мероприятия </a> → Редактирование мероприятия</h4>
        </div>
    </div>

    <div class="createacompetition">
        <h1>Редактирование мероприятия</h1>

        <form action="{{ route('competitions.update', $competition->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Указываем метод PUT для обновления -->

            <!-- Поле для названия мероприятия -->
            <div class="floating-label">
                <label for="name" class="form-label">Название мероприятия</label> <br>
                <input type="text" class="form-control" id="name" name="name" value="{{ $competition->name }}" required>
            </div>

            <!-- Поле для даты мероприятия -->
            <div>
                <label for="date" class="form-label">Дата мероприятия</label> <br>
                <input type="date" class="form-control" id="date" name="date" value="{{ $competition->date }}" required>
            </div>


            <!-- Поле для добавления испытаний (challenges) -->
            <div class="challenges_to_add">
                <h2>Испытания</h2>
                <p class="challenge_choose_label">Выберите одно или несколько испытаний.</p>

                <div class="challenges-container">
                    <a href="{{ route('challenges.create') }}" class="btn btn-secondary">+ Создать новое испытание</a>

                    @foreach ($challenges as $challenge)
                        <div class="challenge-item">
                            <input 
                                type="checkbox" 
                                id="challenge_{{ $challenge->id }}" 
                                name="challenges[]" 
                                value="{{ $challenge->id }}" 
                                {{ in_array($challenge->id, $competition->challenges->pluck('id')->toArray()) ? 'checked' : '' }}
                            >
                            <label for="challenge_{{ $challenge->id }}">{{ $challenge->title }} ({{ $challenge->location }})</label>
                            <button type="button" class="delete-team-btn" onclick="deleteChallenge({{ $challenge->id }})">
                                <img src="../../media/trash.svg" 
                                    data-default="../../media/trash.svg" 
                                    data-hover="../../media/trashred.png" 
                                height="20px" alt="Удалить">
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Поле для добавления команд -->
            <div class="teams_to_add">
                <h2>Команды</h2>
                <p class="team_choose_label">Выберите одну или несколько команд.</p>

                <div class="teams-container">
                    <a href="{{ route('teams.create') }}" class="btn btn-secondary">+ Создать новую команду</a>

                    @foreach ($teams as $team)
                        <div class="team-item">
                            <input 
                                type="checkbox" 
                                id="team_{{ $team->id }}" 
                                name="teams[]" 
                                value="{{ $team->id }}" 
                                {{ in_array($team->id, $competition->participants->pluck('id')->toArray()) ? 'checked' : '' }}
                            >
                            <label for="team_{{ $team->id }}">{{ $team->name }}</label>
                            <button type="button" class="delete-team-btn" onclick="deleteTeam({{ $team->id }})">
                                <img src="../../media/trash.svg" 
                                    data-default="../../media/trash.svg" 
                                    data-hover="../../media/trashred.png" 
                                height="20px" alt="Удалить">
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="judges_to_add">
                <h2>Судейский состав</h2>
                <p class="judge_choose_label">Выберите одного или нескольких судей.</p>
                
                <!-- Скроллящийся контейнер с чекбоксами для судей -->
                <div class="judges-container">
                    <a href="{{ route('judges.create') }}" class="newjudgebtn">+ Новый судья</a>
                    @foreach ($judges as $judge)
                        <div class="judge-item">
                            <input 
                                type="checkbox" 
                                id="judges_{{ $judge->id }}" 
                                name="judges[]" 
                                value="{{ $judge->id }}" 
                                {{ in_array($judge->id, $competition->judges->pluck('id')->toArray()) ? 'checked' : '' }}
                            >
                            <label for="judge_{{ $judge->id }}">{{ $judge->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>





            <button type="submit" class="btn cubebtn">Обновить мероприятие</button>
        </form>
    </div>
</body>

<script>
    function deleteTeam(teamId) {
        if (confirm('Вы уверены, что хотите удалить эту команду?')) {
            fetch(`/teams/${teamId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'include'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const teamElement = document.querySelector(`#team_${teamId}`).closest('.team-item');
                    if (teamElement) {
                        teamElement.remove();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.error || 'Произошла ошибка при удалении команды');
            });
        }
    }

    function deleteChallenge(challengeId) {
        if (confirm('Вы уверены, что хотите удалить это испытание?')) {
            fetch(`/challenges/${challengeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'include'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const challengeElement = document.querySelector(`#challenge_${challengeId}`).closest('.challenge-item');
                    if (challengeElement) {
                        challengeElement.remove();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.error || 'Произошла ошибка при удалении испытания');
            });
        }
    }
</script>


</html>