<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчёт о мероприятии</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Отчёт о мероприятии "{{ $competition->name }}"</h2>
    

    <p>Команды</p>
    
    <table>
        <thead>
            <tr>
                <th>Название команды</th>
                <th>Логин</th>
                <th>Пароль</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $team)
                <tr>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->login }}</td>
                    <td>{{ $team->plain_password }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <br>


    <p>Судейский состав</p>
    
    <table>
        <thead>
            <tr>
                <th>ФИО судьи</th>
                <th>Логин</th>
                <th>Пароль</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($judges as $judge)
                <tr>
                    <td>{{ $judge->name }}</td>
                    <td>{{ $judge->login }}</td>
                    <td>{{ $judge->plain_password }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


</body>
</html>
