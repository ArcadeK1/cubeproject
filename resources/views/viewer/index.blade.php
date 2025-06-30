<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зритель | Система проведения мероприятий IT-Куб.Курган</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="theme-color" content="#fff">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#fff">
    @vite("resources/css/app.css")
    @vite("resources/js/app.js")
    @vite("resources/js/viewerRefresh.js")
</head>
<body>
    <nav class="cubenavbar">
        <ul>
            <li class="logo">
                @php
                   
                        $dashboardaddress = "../";
                        echo("<a href ='$dashboardaddress'>");
                        echo("<img src ='../media/logoold.png' alt='IT-Куб'>");
                        echo("</a>");
                    
                @endphp
            </li class='username'>
                
                <a class="leavebtn" href = "../">
                    <img src="media/exit.jpg" alt="Выход">
                </a>
            </li>
        </ul>
    </nav>

    <div class="eventinfo">


        <div class="viewerwelcome">
            <h1>Теперь вы - Зритель.</h1>
        </div>

        <div class="competitionselector">
            <div class="event-selection">
                <h2>Выберите мероприятие:</h2>
                <input type="date" id="event-date" max="{{ date('Y-m-d') }}">
            </div>
            
            <div class="event-list">
                
                <select id="competition-select">
                    <option value="">Мероприятие</option>
                </select>
            </div>
        </div>
       

            <div class="competitioninfo">
                
                <div class="resultsheader">
                    <h2>Результаты</h2>
                </div>
                
                <div class="challengetable">
                    <table>
                        

                        <tbody>
                            <!-- Заголовок таблицы -->
                            <tr>
                                <th>Испытание </th>
                                @foreach ($teams as $team)
                                    <th>{{ $team->name }}</th>
                                @endforeach
                            </tr>
                        
                            <!-- Данные по компетенциям и результатам команд -->
                            
                            @foreach ($challenges as $challenge)
                                <tr>
                                    <td>{{ $challenge->title }} ({{ $challenge->location }})</td>
                                    @foreach ($teams as $team)
                                        <td data-challenge-id="{{ $challenge->id }}">
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


            </div>

        

    </div>

</body>




</html>