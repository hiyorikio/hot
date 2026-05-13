<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="icon" href="/img/icon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" href="/additionally/styleAgent.css" type="text/css">
    <link rel="stylesheet" href="/additionally/styleIncludes.css" type="text/css">
    <title>Агенты — VALORLIB</title>
</head>

<body>
    <?php include 'includes/header.php';?>

    <?php 
require_once 'config.php';
$selected_role = isset($_GET['role_id']) ? (int)$_GET['role_id'] : 0;
$sql = "SELECT agents.*, roles.name as role_name 
        FROM agents 
        LEFT JOIN roles ON agents.role_id = roles.id";
if ($selected_role > 0) {
    $sql .= " WHERE agents.role_id = $selected_role";
}
$result = $conn->query($sql);
$roles_list = $conn->query("SELECT * FROM roles");
?>

    <main>
        <div class="Content">
            <h2 class="main-title">Галерея агентов</h2>

            <div class="welcom">
                <p>Все агенты Valorant обладают уникальными способностями, которые меняют правила боя. Выберите своего
                    героя
                    и узнайте его историю.</p>
            </div>

            <div class="Filters">
                <a href="?" class="FilterBtn <?php echo $selected_role == 0 ? 'active' : ''; ?>">Все</a>
                <?php while($role = $roles_list->fetch_assoc()): ?>
                <a href="?role_id=<?php echo $role['id']; ?>"
                    class="FilterBtn <?php echo $selected_role == $role['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($role['name']); ?>
                </a>
                <?php endwhile; ?>
            </div>

            <div class="ContentDiv">

                <?php 
                if($result->num_rows > 0) {
                     echo '<div class="ContentCards">';
                 while($row = $result->fetch_assoc()) {
                        $img_folder = "img/cardAgent/";
                        $full_path_img = $img_folder . htmlspecialchars($row['image_card']);
                        $role_display = htmlspecialchars($row['role_name'] ?? 'Неизвестная роль');
            ?>
                <div class="CardBasic">
                    <div class="image">
                        <img src="<?php echo $full_path_img; ?>">
                        <span class="SPNRole"><?php echo $role_display; ?></span>
                    </div>
                    <div class="cardInfo">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                    </div>
                    <a href="agent_detail.php?id=<?php echo $row['id']; ?>" class="external-link">
                        Подробнее ➤
                    </a>
                </div>
                <?php 
    }
    echo '</div>';
} else {
    echo "<p>Агенты данной роли не найдены.</p>";
}
?>

            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>

</html>