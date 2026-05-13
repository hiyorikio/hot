<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/additionally/styleIncludes.css" type="text/css">
    <link rel="stylesheet" href="/additionally/styleFacts.css" type="text/css">
    <title>Интересные факты — VALORLIB</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <?php 
require_once 'config.php';
$sql = "SELECT * FROM easter";
$result = $conn->query($sql);
?>

    <main>
        <div class="facts-content">
            <h2 class="main-title">ИНТЕРЕСНЫЕ ФАКТЫ</h2>

            <div class="welcom">
                <p>Вселенная VALORANT полна секретов, пасхалок и неочевидных деталей. Мы собрали для вас самые
                    интересные факты об агентах, картах и разработке игры, которые вы могли пропустить.</p>
            </div>

            <?php if ($result && $result->num_rows > 0): ?>
            <div class="facts-list">
                <?php 
                while($row = $result->fetch_assoc()): 
                    $media_folder = "img/Easter/";
                    $filename = htmlspecialchars($row['media']);
                    $full_path = $media_folder . $filename;
                    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $video_extensions = ['mp4', 'webm', 'ogg'];
                    $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    // Чередуем классы на основе ID
                    $alt_class = ($row['id_easter'] % 2 == 0) ? '' : 'alt';
                ?>
                <div class="fact-item <?= $alt_class ?>">
                    <div class="fact-media">
                        <?php if (!empty($filename)): ?>
                        <?php if (in_array($file_extension, $video_extensions)): ?>
                        <video loop muted autoplay playsinline>
                            <source src="<?= $full_path ?>">
                        </video>
                        <?php elseif (in_array($file_extension, $image_extensions)): ?>
                        <img src="<?= $full_path ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="fact-info">
                        <div class="fact-header">
                            <div class="fact_numer">#<?= htmlspecialchars($row['id_easter']) ?></div>
                            <h2 class="fact-title"><?= htmlspecialchars($row['name']) ?></h2>
                        </div>
                        <p class="fact-desc"><?= htmlspecialchars($row['description']) ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>

</html>