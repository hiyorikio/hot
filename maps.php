<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
        <link rel="stylesheet" href="additionally/styleMaps.css" type="text/css">
        <link rel="stylesheet" href="/additionally/styleIncludes.css" type="text/css">
        <title>Карты — VALORLIB</title>
    </head>
    <body>

    <?php include 'includes/header.php'; ?>

    <?php
// Подключение к БД
require_once 'config.php';
$sql = "SELECT m.*, 
        (SELECT GROUP_CONCAT(image_path ORDER BY id) FROM map_images WHERE map_id = m.id) as images
        FROM maps m 
        ORDER BY m.id";

$result = $conn->query($sql);
$maps = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['images'] = $row['images'] ? explode(',', $row['images']) : [];
        $maps[] = $row;
    }
}
?>

    <main>
    <div class="Content">
        <h2 class="main-title">КАРТЫ</h2>
        
        <div class="welcom">
            <p>В Valorant на данный момент доступно <?php echo count($maps); ?> карт. 
                <br>Новые карты добавляются довольно редко, но это не значит, что Вам надоест играть на одних и тех же картах – они случайным образом меняются в каждом матче.</p>
        </div>

        <?php foreach ($maps as $map): ?>
        <div class="MapDiv">
            <h2 class="title"><?php echo htmlspecialchars($map['name']); ?></h2>
            <div class="infoMap">
                <div class="dopInfo">
                    <p>Дата выхода: <?php echo htmlspecialchars($map['release_date']); ?></p>
                    <p>
                        <img src="img/iconGeo.png">
                        <?php echo htmlspecialchars($map['location']); ?>
                    </p>
                </div>
                <p><?php echo htmlspecialchars($map['description'] ?? ''); ?></p>
            </div>
            
            <div class="slider-container">
                <div class="slider">
                    <?php foreach ($map['images'] as $image): ?>
                    <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($map['name']); ?>">
                    <?php endforeach; ?>
                </div>
                    <button class="prev-btn">➢</button>
                    <button class="next-btn">➣</button>
                <div class="slider-dots"></div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</main>

    <script src="additionally/slider.js"></script>

    <?php include 'includes/footer.php'; ?>
    
    <?php $conn->close(); ?>
</body>
</html>