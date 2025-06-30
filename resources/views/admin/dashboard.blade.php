<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <!-- iOS (Safari) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#ffffff">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Кабинет администратора | Система проведения мероприятий IT-Куб.Курган</title>
    @vite("resources/css/app.css")
    @vite("resources/js/app.js")
    @vite("resources/js/trashButton.js")
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



    <div class="adminroom">
        <div class="adminevents">


            <div class="switcher">
                <p style = "margin-left:7px;">Мероприятия</p>
                <label class="switch">
                    <input type="checkbox" id="toggleactivities">
                    <span class="onoff"></span>
                </label>
                <p>Судьи</p>
            </div>

            <div id="allcompetitions">
                <h2>Мероприятия</h2>
                <!-- Вывод всех испытаний -->
         
                        @if ($competitions->isEmpty())
                            <h5>Пока нет мероприятий.</h5>
                        @else
                                @foreach ($competitions as $competition)
                                    <div class="cubecompetition">
                                        <!-- Добавьте кнопки для редактирования или удаления -->
                                        <div class="cubecompetition_title">
                                            <a href="{{ route('competitions.info', $competition->id) }}">{{ $competition->name }}</a>
                                        </div>
                                        <form action="{{ route('competitions.destroy', $competition->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить это мероприятие?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="deletebtn">
                                                <img src="../media/trash.svg" 
                                                     data-default="../media/trash.svg" 
                                                     data-hover="../media/trashred.png" 
                                                     height="50px" alt="Удалить">
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                        @endif
                        
                
                    <div class="neweventbutton">
                        <a href="{{ route('competitions.create') }}" class="cubebtn">Новое мероприятие</a>
                    </div>
                
            </div>



            <div id="alljudges" style="display: none">
                <h2>Судейский состав</h2>
                <!-- Вывод всех судей -->
         
                        @if ($judges->isEmpty())
                            <h5>Пока нет мероприятий.</h5>
                        @else
                                @foreach ($judges as $judge)
                                    <div class="cubecompetition">
                                        <!-- Добавьте кнопки для редактирования или удаления -->
                                        <div class="cubecompetition_title">
                                            <p>{{ $judge->name }}</p>
                                        </div>

                                        <form action="{{ route('judges.destroy', $judge->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этого судью?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="deletebtn">
                                                <img src="../media/trash.svg" 
                                                     data-default="../media/trash.svg" 
                                                     data-hover="../media/trashred.png" 
                                                     height="50px" alt="Удалить">
                                            </button>
                                        </form>

                                    </div>
                                @endforeach
                        @endif
                        
                
                    <div class="neweventbutton">
                        <a href="{{ route('judges.create') }}" class="cubebtn">Добавить судью</a>
                    </div>
                
            </div>
            
                    
        </div>

    </div>
</body>



<script>
    document.addEventListener('DOMContentLoaded', () => {
    const toggleCheckbox = document.getElementById('toggleactivities');
    const allCompetitions = document.getElementById('allcompetitions');
    const allJudges = document.getElementById('alljudges');

    toggleCheckbox.addEventListener('change', () => {
        if (toggleCheckbox.checked) {
            allCompetitions.style.display = 'none';
            allJudges.style.display = 'block';

        } else {
            allCompetitions.style.display = 'block';
            allJudges.style.display = 'none';
            
        }
    });
});

</script>

</html>
