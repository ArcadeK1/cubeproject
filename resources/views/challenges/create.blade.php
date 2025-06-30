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
    <title>Создание испытания | Система проведения мероприятий IT-Куб.Курган</title>
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
            <h4><a href="../admin/dashboard">События</a> -> Новое испытание</h4>
        </div>
    </div>

    <div class="createacompetition">
        <h1>Новое испытание</h1>

        

        <form action="{{ route('challenges.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        
            <!-- Поле для названия испытания -->
            <div class="floating-label">
                <label for="title" class="form-label">Название испытания</label> <br>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
        
            <!-- Поле для локации испытания -->
            <div class="floating-label">
                <label for="location" class="form-label">Локация</label> <br>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
        
            <!-- Поле для баллов -->
            <div class="floating-label">
                <label for="points" class="form-label">Баллы</label> <br>
                <input type="number" class="form-control" id="points" name="points" required>
            </div>
        
            <!-- Поле для выбора мероприятия -->

        
            <button type="submit" class="btn cubebtn">Создать испытание</button>
        </form>
    </div>
</body>
</html>