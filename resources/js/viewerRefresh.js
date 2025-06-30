document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("event-date");
    const competitionSelect = document.getElementById("competition-select");
    const tableBody = document.querySelector(".challengetable table tbody");

    let currentCompetitionId = null;
    let updateInterval = null;

    // Устанавливаем текущую дату по умолчанию
    const today = new Date().toISOString().split("T")[0];
    dateInput.value = today;

    // Функция загрузки мероприятий по дате
    function loadCompetitions(date) {
        fetch(`/viewer/competitions?date=${date}`)
            .then(response => response.json())
            .then(data => {
                competitionSelect.innerHTML = "<option value=''>Выберите мероприятие</option>";
                data.competitions.forEach(comp => {
                    const option = document.createElement("option");
                    option.value = comp.id;
                    option.textContent = comp.name;
                    competitionSelect.appendChild(option);
                });

                // Если есть мероприятия, выбираем первое и загружаем его данные
                if (data.competitions.length > 0) {
                    competitionSelect.value = data.competitions[0].id;
                    updateCompetitionData(data.competitions[0].id);
                } else {
                    tableBody.innerHTML = "<tr><td colspan='100%'>Мероприятий нет</td></tr>";
                    stopAutoUpdate();
                }
            })
            .catch(error => console.error("Ошибка загрузки мероприятий:", error));
    }

    // Функция загрузки данных мероприятия
    function loadCompetitionData(competitionId) {
        if (!competitionId) return;

        fetch(`/viewer/data?competition_id=${competitionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.challenges.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='100%'>Нет данных</td></tr>";
                    return;
                }

                let headerRow = "<tr><th>Испытание</th>";
                data.teams.forEach(team => {
                    headerRow += `<th>${team.name}</th>`;
                });
                headerRow += "</tr>";
                tableBody.innerHTML = headerRow;

                data.challenges.forEach(challenge => {
                    let row = `<tr><td>${challenge.title} (${challenge.location})</td>`;
                    data.teams.forEach(team => {
                        let score = data.scoresByChallenge[challenge.id]?.teams[team.id]?.total_score || 0;
                        row += `<td>${score}/${challenge.points}</td>`;
                    });
                    row += "</tr>";
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error("Ошибка загрузки данных мероприятия:", error));
    }

    // Запуск автообновления данных
    function startAutoUpdate(competitionId) {
        stopAutoUpdate();
        updateInterval = setInterval(() => {
            loadCompetitionData(competitionId);
        }, 4000);
    }

    // Остановка автообновления
    function stopAutoUpdate() {
        if (updateInterval) {
            clearInterval(updateInterval);
            updateInterval = null;
        }
    }

    // Обновление данных мероприятия при выборе
    function updateCompetitionData(competitionId) {
        if (currentCompetitionId !== competitionId) {
            currentCompetitionId = competitionId;
            loadCompetitionData(competitionId);
            startAutoUpdate(competitionId);
        }
    }

    // При изменении даты загружаем мероприятия
    dateInput.addEventListener("change", () => {
        loadCompetitions(dateInput.value);
    });

    // При выборе мероприятия загружаем его данные и включаем автообновление
    competitionSelect.addEventListener("change", () => {
        updateCompetitionData(competitionSelect.value);
    });

    // Загружаем данные по умолчанию (сегодня)
    loadCompetitions(today);
});
