<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <title>{{ $competition->name }} | Система проведения мероприятий IT-Куб.Курган</title>
    @vite("resources/css/app.css")
</head>
<body>

    <nav class="cubenavbar">
        <ul>
            <li class = 'logo'>
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
            <li class = 'username'>
                <p style="">{{ auth()->user()->name }}</p>
                <form style="align-items: center" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="outtaherebtn">
                        <img src = "../media/exit.jpg" alt = "Выход">
                    </button>
                </form>
            </li>
        </ul>
    </nav>



    <div class="eventinfo">

        <div class="basicname">  
            <div class="minidirections">

                <h4><a href="../admin/dashboard">Мероприятия</a> → {{ $competition->name }}</h4>

            </div>

            <div class="nameandedit">
                <h1>{{ $competition->name }}</h1>

                <a href="{{ route('competitions.edit', $competition->id) }}" class="editbtn">
                    <img src = "../media/editicon.png" alt="Редактировать">
                </a>
            </div>
            <hr>
        </div>


        <div class="competitioninfo">
            
            <div class="challengetable-wrapper" style="overflow-x: auto; max-width: 100%;"> 
                
                <div class="resultsheader">
                    <h2>Результаты</h2>
                </div>
                <div class="challengetable">
                    <table>
                        <tbody>
                            <!-- Заголовок таблицы -->
                            <tr>
                                <th>Компетенция</th>
                                @foreach ($teams as $team)
                                    <th>{{ $team->name }}</th>
                                @endforeach
                            </tr>
                        
                            <!-- Данные по компетенциям и результатам команд -->
                            
                            @foreach ($challenges as $challenge)
                                <tr>
                                    <td>{{ $challenge->title }} ({{ $challenge->location }})</td>
                                    @foreach ($teams as $team)
                                        <td>
                                            @if (isset($scoresByChallenge[$challenge->id]['teams'][$team->id]))
                                                {{ $scoresByChallenge[$challenge->id]['teams'][$team->id]['total_score'] }}/{{ $challenge->points }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


                <div class="competitionjudges">
                    <h3>Судейский состав</h3>
                    <ol>
                        @forelse ($judges as $judge)
                            <li>{{ $judge->name }}</li>
                        @empty
                            <li>Судьи не назначены</li>
                        @endforelse
                    </ol>
                </div>

                <a href="{{ route('competitions.report', ['competitionID' => $competition->id]) }}" class="pdfbtn" target="_blank">
                    Скачать отчёт PDF
                </a>

            </div>

        </div>

    </div>
</body>
</html>