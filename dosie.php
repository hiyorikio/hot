<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/additionally/styleIncludes.css">
    <link rel="stylesheet" href="/additionally/styleDosie.css">
    <title>Досье агентов — VALORLIB</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <?php 
require_once 'config.php';

// Получаем список агентов
$agents = $conn->query("SELECT id, name, role_id FROM agents ORDER BY name");
$selected_id = isset($_GET['agent_id']) ? (int)$_GET['agent_id'] : 0;

if ($selected_id == 0 && $agents->num_rows > 0) {
    $agents->data_seek(0);
    $selected_id = $agents->fetch_assoc()['id'];
    $agents->data_seek(0);
}

// Получаем данные агента
$agent = $conn->query("SELECT agents.*, roles.name as role_name FROM agents LEFT JOIN roles ON agents.role_id = roles.id WHERE agents.id = $selected_id")->fetch_assoc();
?>

    <main>
        <div class="Content">
            <h2 class="title">СЕКРЕТНОЕ ДОСЬЕ</h2>
            <div class="welcome">
                <p>Протокол VALORANT — строго засекреченная организация. Ниже представлены рассекреченные файлы на
                    агентов. Доступ ограничен.</p>
            </div>

            <div class="container">
                <!-- Боковая панель -->
                <aside class="sidebar">
                    <div class="sidebar-header">
                        <h3>АГЕНТЫ</h3>
                        <p>Выберите объект для изучения</p>
                    </div>
                    <div class="agents-list">
                        <?php if($agents && $agents->num_rows > 0): ?>
                        <?php while($a = $agents->fetch_assoc()): ?>
                        <a href="?agent_id=<?= $a['id'] ?>"
                            class="agent <?= ($selected_id == $a['id']) ? 'active' : '' ?>">
                            <div class="code">➣</div>
                            <div class="name"><?= htmlspecialchars($a['name']) ?></div>
                        </a>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="empty">Агенты не найдены</div>
                        <?php endif; ?>
                    </div>
                </aside>

                <!-- Основной блок -->
                <div class="DivMain">
                    <?php if($agent): ?>
                    <div class="header">
                        <div class="badge">СОВЕРШЕННО СЕКРЕТНО</div>
                        <div class="agent-info">
                            <div class="portrait">
                                <img src="img/cardAgent/<?= htmlspecialchars($agent['image_card']) ?>"
                                    alt="<?= htmlspecialchars($agent['name']) ?>">
                            </div>
                            <div class="identity">
                                <h1><?= htmlspecialchars($agent['name']) ?></h1>
                                <div class="details">
                                    <div><span class="label">Кодовое
                                            имя:</span><span><?= htmlspecialchars($agent['name']) ?></span></div>
                                    <div><span class="label">Настоящее
                                            имя:</span><span><?= htmlspecialchars($agent['real_name']) ?></span></div>
                                    <div><span class="label">Роль:</span><span
                                            class="role"><?= htmlspecialchars($agent['role_name'] ?? 'Неизвестно') ?></span>
                                    </div>
                                    <div><span
                                            class="label">ID:</span><span><?= htmlspecialchars($agent['id_val']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="divBody">
                        <div class="section">
                            <h3>📋 ДОСЬЕ</h3>
                            <div class="desc">
                                <?= nl2br(htmlspecialchars($agent['full_story'] ?? 'Информация временно недоступна.')) ?>
                            </div>
                        </div>
                        <div class="section redacted">
                            <h3>🔒 ДОПОЛНИТЕЛЬНЫЕ ДАННЫЕ</h3>
                            <p>Сведения о происхождении, персональные данные и история операций засекречены.</p>
                            <div class="redacted-line"><?= nl2br(htmlspecialchars($agent['additional_data'])) ?></div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="error">Агент не найден в базе данных.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>