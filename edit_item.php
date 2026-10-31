<?php
require_once 'core/models.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$item = getItemById($pdo, $_GET['id'] ?? 0);
if (!$item) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Item</title>
  <link rel="stylesheet" href="design_style.css">
</head>
<body class="center-page">
  <div class="box">
    <h2>Edit Item</h2>
    <form action="core/handleForms.php" method="POST">
      <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
      <input type="hidden" name="category_id" value="<?= $item['category_id'] ?>">
      <?php if (($_GET['from'] ?? '') === 'index') { ?>
        <input type="hidden" name="from_index" value="1">
      <?php } ?>
      <div class="field"><label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required></div>
      <div class="field"><label>Price</label>
        <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" required></div>
      <div class="field"><label>Details</label>
        <input type="text" name="details" value="<?= htmlspecialchars($item['details'] ?? '') ?>"></div>
      <input type="submit" name="updateItemBtn" value="Update Item">
    </form>
    <?php if (($_GET['from'] ?? '') === 'index') { ?>
      <a class="back" href="index.php">&larr; Back</a>
    <?php } else { ?>
      <a class="back" href="item.php?category_id=<?= $item['category_id'] ?>">&larr; Back</a>
    <?php } ?>
  </div>
</body>
</html>