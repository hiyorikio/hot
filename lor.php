<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="/additionally/styleIncludes.css" type="text/css">
    <link rel="stylesheet" href="/additionally/styleLor.css" type="text/css">
    <title>Лор вселенной — VALORLIB</title>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <?php
// Подключение к БД
require_once 'config.php';

// Используем $conn вместо $mysqli
$query = "
    SELECT 
        e.id_ep,
        e.number as episode_num,
        e.name as episode_name,
        p.text as description,
        p.media_url as media_file,
        p.media_alt as media_alt,
        p.media_type
    FROM episodes e
    LEFT JOIN paragraphs p ON e.id_ep = p.id_ep
    ORDER BY e.id_ep, p.id_par
";

$stmt = mysqli_query($conn, $query);

if (!$stmt) {
    die("Ошибка запроса: " . mysqli_error($conn));
}

$episodes_data = [];

// Группируем параграфы по эпизодам
while ($row = mysqli_fetch_assoc($stmt)) {
    $ep_id = $row['id_ep'];
    if (!isset($episodes_data[$ep_id])) {
        $episodes_data[$ep_id] = [
            'number' => $row['episode_num'],
            'name' => $row['episode_name'],
            'paragraphs' => []
        ];
    }
    if ($row['description']) {
        $episodes_data[$ep_id]['paragraphs'][] = [
            'text' => $row['description'],
            'media_url' => $row['media_file'],
            'media_alt' => $row['media_alt'],
            'media_type' => $row['media_type']
        ];
    }
}
?>

    <main>
        <div class="Content">
            <h2 class="main-title">ЛОР ВСЕЛЕННОЙ<br>VALORANT</h2>
            <div class="welcom">
                <p>История VALORANT довольно сложна, поскольку официальная информация от разработчиков предоставляется
                    нечасто и в ограниченных объемах. Тем не менее, на сегодняшний день вышло 9 эпизодов и 2 сезона игры. Давайте
                    отправимся в мир VALORANT и ознакомимся с его лором.</p>
            </div>

            <?php foreach ($episodes_data as $episode): ?>
    <section class="lor-section">
        <div class="section-numer"><?= htmlspecialchars($episode['number']) ?></div>
        <div class="lor-content">
            <h3><?= htmlspecialchars($episode['name']) ?></h3>
            
            <?php foreach ($episode['paragraphs'] as $paragraph): ?>
                <div class="paragraph-block">
                    <p><?= nl2br(htmlspecialchars($paragraph['text'])) ?></p>
                    
                    <?php if (!empty($paragraph['media_url'])): ?>
                        <figure class="media-section">
                            <?php 
                            $ext = strtolower(pathinfo($paragraph['media_url'], PATHINFO_EXTENSION));
                            if (in_array($ext, ['mp4', 'webm', 'ogg'])): 
                            ?>
                                <video controls loop muted playsinline>
                                    <source src="<?= htmlspecialchars($paragraph['media_url']) ?>" type="video/<?= $ext ?>">
                                    Ваш браузер не поддерживает видео.
                                </video>
                            <?php else: ?>
                                <img src="<?= htmlspecialchars($paragraph['media_url']) ?>" 
                                     alt="<?= htmlspecialchars($paragraph['media_alt'] ?: 'Медиа') ?>">
                            <?php endif; ?>
                            <?php if (!empty($paragraph['media_alt'])): ?>
                                <figcaption><?= htmlspecialchars($paragraph['media_alt']) ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>

        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>