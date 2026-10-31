<?php
require_once 'core/models.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$cat = getCategoryById($pdo, $_GET['category_id'] ?? 0);
if (!$cat) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Items</title>
  <link rel="stylesheet" href="design_style.css">
</head>
<body>
<div class="container">
  <div class="topbar">
    <h1>&#9749; <?= htmlspecialchars($cat['name']) ?> Items</h1>
    <div>
      <span class="user"><?= htmlspecialchars($_SESSION['username']) ?></span>
      <a class="logout" href="core/handleForms.php?logoutAUser=1">Logout</a>
    </div>
  </div>

  <a class="back" href="index.php">&larr; Back to categories</a>

  <h3>Add Item</h3>
  <form class="add-form" action="core/handleForms.php" method="POST">
    <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
    <input type="text" name="name" placeholder="Item name (e.g. Caramel Latte)" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="text" name="details" placeholder="Details (e.g. 12oz, iced)">
    <input type="submit" name="insertItemBtn" value="Add Item">
  </form>

  <h3>All Items</h3>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Item</th><th>Price</th><th>Details</th><th>Added by</th><th>Last updated by</th><th>Last updated</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php $items = getItemsByCategory($pdo, $cat['category_id']); ?>
        <?php if (count($items) === 0) { ?>
          <tr><td class="empty" colspan="7">No items yet. Add one above.</td></tr>
        <?php } ?>
        <?php foreach ($items as $row) { ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= number_format($row['price'], 2) ?></td>
          <td><?= htmlspecialchars($row['details'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['added_by_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['updated_by_name'] ?? '') ?></td>
          <td><?= $row['last_updated'] ?></td>
          <td class="actions">
            <a class="btn-edit"
               href="edit_item.php?id=<?= $row['item_id'] ?>"
               data-edit="item"
               data-id="<?= (int)$row['item_id'] ?>"
               data-category-id="<?= (int)$cat['category_id'] ?>"
               data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>"
               data-price="<?= htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') ?>"
               data-details="<?= htmlspecialchars($row['details'] ?? '', ENT_QUOTES, 'UTF-8') ?>">Edit</a>
            <a class="btn-del" href="core/handleForms.php?deleteItemId=<?= $row['item_id'] ?>&category_id=<?= $cat['category_id'] ?>"
               data-confirm="Delete this item?">Delete</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="logout.js"></script>
</body>
</html>