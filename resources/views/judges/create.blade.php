<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить судью | Система проведения мероприятий IT-Куб.Курган</title>
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    @vite("resources/css/app.css")
    @vite("resources/js/app.js")
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class="logo">
                @php
                    $dashboardaddress = '';
                    if(auth()->user()->role=='admin')
                    {
                        $dashboardaddress = "../admin/dashboard";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../media/logoold.png' alt='IT-Куб'>");
                        echo("</a>");
                    }
                @endphp
            </li>
            <li class="username">
                <p>{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="outtaherebtn">
                        <img src="../media/exit.jpg" alt="Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <div class="basicname">  
        <div class="minidirections">
            <h4><a href="../admin/dashboard">Мероприятия</a> -> Добавить судью </h4>
        </div>
    </div>

    <div class="createacompetition">
        <h1>Добавить судью </h1>

        <!-- Отображение сообщений об ошибках -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Отображение сообщения об успехе и данных для входа -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <p><strong>ФИО судьи:</strong> {{ session('name') }}</p>
                <p><strong>Логин:</strong> {{ session('login') }}</p>
                <p><strong>Пароль:</strong> {{ session('password') }}</p>
            </div>
        @endif

        <form action="{{ route('judges.store') }}" method="POST">
            @csrf

            <!-- Поле для имени команды -->
            <div class="floating-label">
                <label for="name" class="form-label">ФИО судьи</label> <br>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <button type="submit" class="btn cubebtn">Создать судью</button>
        </form>
    </div>
</body>
</html>