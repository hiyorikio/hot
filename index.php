<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="/additionally/styleIndex.css" type="text/css">
    <link rel="stylesheet" href="/additionally/styleIncludes.css" type="text/css">
    <title>VALORLIB</title>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <?php
    require_once 'config.php';
    ?>

    <main>
        <div class="MainDiv">
            <video loop muted autoplay playsinline class="videoFon">
                <source src="video/12_05_Homescreen.mp4" type="video/mp4">
                <source src="video/12_05_Homescreen.webm" type="video/webm">
                Ваш браузер не поддерживает видео.
            </video>
            <div class="NavigatDiv">
                <img src="img/MainImg.png" alt="Main Image">
                <p>Подгрузитесь в богатый лор, изучите уникальных агентов и стратегические карты тактического шутера от
                    Riot
                    Games. Откройте для себя конфликт между мирами Альфа и Омега, сражение за радиант и историю
                    протокола
                    VALORANT.</p>
                <button id="btnMain">Исследовать вселенную</button>
            </div>
        </div>

        <section id="sctBasic" class="ContentBlock" style="background: linear-gradient(to right, #1D2934, #0f1923)">
            <h2>Что вас ждёт</h2>
            <div class="cardsDiv">
                <?php
                $result = mysqli_query($conn, "SELECT * FROM sections");
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                <div class="cardBasic">
                    <div class="cardIcon">
                        <img src="<?php echo htmlspecialchars($row['icon_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </div>
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <a href="<?php echo htmlspecialchars($row['link']); ?>" class="cardLink">Подробнее ➤</a>
                </div>
                <?php 
                    endwhile;
                } else {
                    echo '<p>Нет доступных разделов.</p>';
                }
                ?>
            </div>
        </section>

        <section class="ContentBlock" style="background: linear-gradient(to left, #1D2934, #0f1923)">
            <h2>Обновления и патчи</h2>
            <div class="cardsDivPatch">
                <?php
                // Исправлено: сортировка по id вместо отсутствующей колонки patch_date
                $result = mysqli_query($conn, "SELECT * FROM updates ORDER BY id DESC LIMIT 4");
                
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                <a href="<?php echo htmlspecialchars($row['external_link']); ?>" target="_blank" rel="noopener noreferrer">
                    <div class="cardPatch">
                        <div class="cardIconPatch">
                            <?php if (!empty($row['video_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $row['video_path'])): ?>
                            <video loop muted autoplay playsinline>
                                <source src="<?php echo htmlspecialchars($row['video_path']); ?>" type="video/mp4">
                                Ваш браузер не поддерживает видео.
                            </video>
                            <?php else: ?>
                            <div class="placeholder-image"></div>
                            <?php endif; ?>
                            <div class="cardHeader">
                                <span><?php echo htmlspecialchars($row['badge_text']); ?></span>
                                <?php if (!empty($row['patch_version'])): ?>
                                <span><?php echo htmlspecialchars($row['patch_version']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                </a>
                <?php 
                    endwhile;
                } else {
                    echo '<p>Нет доступных обновлений.</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <?php 
    mysqli_close($conn);
    include 'includes/footer.php'; 
    ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('btnMain');
        const sectorAgent = document.getElementById('sctBasic');
        
        if (btn && sectorAgent) {
            btn.addEventListener('click', function() {
                sectorAgent.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            });
        }
    });
    </script>
</body>

</html>