<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/additionally/styleIncludes.css">
    <link rel="stylesheet" href="/additionally/styleDosie2.css">
    <title>Секретное досье — VALORLIB</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <?php 
require_once 'config.php';

// Максимально простой запрос без JOIN. Берем только таблицу agents.
$sql = "SELECT agents.*, roles.name as role_name 
        FROM agents 
        LEFT JOIN roles ON agents.role_id = roles.id";;
$result = $conn->query($sql);

$agents_data = array();

// Собираем массив, где ключ - имя агента маленькими буквами
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $name_key = htmlspecialchars($row['name']);
        $agents_data[$name_key] = $row;
    }
}

// Отправляем данные в JS
$agents_json = json_encode($agents_data, JSON_UNESCAPED_UNICODE);
?>

    <main>
        <div class="Content">
            <h2 class="main-title">СЕКРЕТНОЕ ДОСЬЕ</h2>
            <div class="welcome">
                <p>Протокол VALORANT — строго засекреченная организация. Чтобы узнать, кто входит в Протокол и какое
                    прошлое их преследует, обратитесь к базе данных Cypher.</p>
            </div>

            <!-- Интерфейс консоли -->
            <div class="console-wrapper">
                <div class="console-header">
                    <span>VALORANT OS v 12.08</span>
                </div>
                <div class="console-body" id="console-output">
                    <span class="sys-msg">VALORANT Protocol Secret 1.29.7</span>
                    <p class="sys-msg">Введите <b>help</b> для вывода списка команд.</p>
                </div>
                <div class="console-input-area">
                    <span class="prompt">C:\VALORANT></span>
                    <input type="text" id="console-input" autocomplete="off">
                </div>
            </div>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
    // Данные из базы
    const agents = <?php echo $agents_json; ?>;

    const input = document.getElementById('console-input');
    const output = document.getElementById('console-output');

    // Простая функция для добавления текста в консоль
    function printMsg(text, cssClass = '') {
        // Добавляем новый параграф с текстом
        output.innerHTML += `<p class="${cssClass}">${text}</p>`;
        // Скроллим вниз
        output.scrollTop = output.scrollHeight;
    }

    // Отслеживаем нажатие клавиш в поле ввода
    input.addEventListener('keypress', function(event) {
        // Если нажали Enter (код 13)
        if (event.key === 'Enter') {
            // Получаем текст, убираем пробелы по краям и делаем маленькие буквы
            let cmd = input.value.trim();

            // Очищаем поле ввода
            input.value = '';

            // Если ничего не ввели, ничего не делаем
            if (cmd === '') return;

            // Выводим саму команду на экран
            printMsg('C:\\VALORANT> ' + cmd, 'user-cmd');

            // Разбиваем строку на слова (массив)
            let parts = cmd.split(' ');
            let action = parts[0]; // Первое слово (команда)
            let agentName = parts[1]; // Второе слово (имя агента)

            // Простые проверки условий
            if (action === 'help') {
                printMsg('Команды: list, info [имя], secret [имя], clear', 'sys-msg');
            } else if (action === 'list') {
                // Получаем все ключи (имена) из объекта
                let names = Object.keys(agents).join(', ');
                printMsg('Доступные позывные: ' + names, 'sys-msg');
            } else if (action === 'info') {
                if (agents[agentName]) {
                    printMsg('ПОЗЫВНОЙ: ' + agents[agentName].name);
                    printMsg('VAL ID: ' + agents[agentName].id_val);
                    printMsg('Роль: ' + agents[agentName].role_name);
                } else {
                    printMsg('Ошибка: Агент не найден.', 'error-msg');
                }
            } else if (action === 'secret') {
                if (agents[agentName]) {
                    printMsg('ДОСТУП РАЗРЕШЕН', 'success-msg');
                    printMsg('НАСТОЯЩЕЕ ИМЯ: ' + agents[agentName].real_name);
                    printMsg('ИСТОРИЯ: ' + agents[agentName].description);
                    printMsg('ОПИСАНИЕ: ' + agents[agentName].full_story);
                } else {
                    printMsg('Ошибка: Агент не найден.', 'error-msg');
                }
            } else if (action === 'clear') {
                // Очищаем содержимое блока
                output.innerHTML = '';
            } else {
                printMsg('Неизвестная команда.', 'error-msg');
            }
        }
    });
    </script>
</body>

</html>