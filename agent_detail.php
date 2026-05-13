<?php 
require_once 'config.php';
$agent_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 1. Получаем основные данные агента
$sql_agent = "SELECT agents.*, roles.name as role_name FROM agents 
              LEFT JOIN roles ON agents.role_id = roles.id WHERE agents.id = $agent_id";
$result_agent = $conn->query($sql_agent);
$agent = $result_agent->fetch_assoc();

if (!$agent) { die("Агент не найден."); }

// 2. Получаем список способностей (вместо json_decode)
$sql_abilities = "SELECT * FROM abilities WHERE agent_id = $agent_id";
$abilities_result = $conn->query($sql_abilities);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="/additionally/styleAgentDetails.css">
    <link rel="stylesheet" href="/additionally/styleIncludes.css" type="text/css">
    <title><?php echo htmlspecialchars($agent['name'] ?? 'Агент не найден'); ?> - VALORLIB</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="Content">
        <h2 class="main-title"><?php echo htmlspecialchars($agent['name']); ?></h2>

        <div class="agent-profile-grid">
            <!-- Левая панель с фото (стиль fact-item) -->
            <aside class="agent-side-card">
                <img src="/img/cardAgent/<?php echo $agent['image_card']; ?>" class="agent-photo">
                <div class="technical-data">
                    <div class="data-row">
                        <span class="label">СТРАНА:</span>
                        <span class="val"><?php echo $agent['country']; ?></span>
                    </div>
                    <div class="data-row">
                        <span class="label">РОЛЬ:</span>
                        <span class="val"><?php echo $agent['role_name']; ?></span>
                    </div>
                    <div class="data-row">
                        <span class="label">ID:</span>
                        <span class="val"><?php echo $agent['id_val']; ?></span>
                    </div>
                </div>
            </aside>

            <!-- Правая панель (стиль welcom) -->
            <div class="agent-bio">
                <h3 class="main-title" style="font-size: 2rem;">ХАРАКТЕРИСТИКА</h3>
                <div class="welcom">
                    <p><?php echo nl2br(htmlspecialchars($agent['additional_data'])); ?></p>
                </div>
                
                <h3 class="main-title" style="font-size: 2rem;">СПОСОБНОСТИ</h3>

<div class="abilities-list">
    <?php if ($abilities_result->num_rows > 0): ?>
        <?php while($ability = $abilities_result->fetch_assoc()): ?>
        <div class="ability-item">
            <div class="ability-media">
                <?php if(!empty($ability['video_url'])): ?>
                    <video src="<?php echo htmlspecialchars($ability['video_url']); ?>" autoplay muted loop></video>
                <?php else: ?>
                    <img src="/img/placeholder.jpg">
                <?php endif; ?>
            </div>
            <div class="ability-info">
                <div class="ability-header">
                    <span class="ability-type"><?php echo htmlspecialchars($ability['ability_key']); ?></span>
                    <h4 class="ability-title"><?php echo htmlspecialchars($ability['name']); ?></h4>
                </div>
                <p class="ability-desc"><?php echo nl2br(htmlspecialchars($ability['description'])); ?></p>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Данные о способностях уточняются...</p>
    <?php endif; ?>
</div>

                <a href="dosie.php" class="back-link">← ВЕРНУТЬСЯ В АРХИВ</a>
            </div>
        </div>
    </main>
</body>
</html>