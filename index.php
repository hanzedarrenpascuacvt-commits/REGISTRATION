<?php
require_once 'core/models.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
$categories = getAllCategories($pdo);

function renderMenuPanel($pdo, $cat) {
    ?>
    <div class="panel">
      <h3>Add Item to <?= htmlspecialchars($cat['name']) ?></h3>

      <form class="add-form" action="core/handleForms.php" method="POST">
        <input type="hidden" name="category_id" value="<?= $cat['category_id'] ?>">
        <input type="hidden" name="from_index" value="1">
        <input type="text" name="name" placeholder="Item name" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="text" name="details" placeholder="Details">
        <input type="submit" name="insertItemBtn" value="Add Item">
      </form>

      <h3><?= htmlspecialchars($cat['name']) ?> Items</h3>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Name</th><th>Price</th><th>Details</th><th>Added by</th><th>Last updated by</th><th>Last updated</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php $items = getItemsByCategory($pdo, $cat['category_id']); ?>
            <?php if (count($items) === 0) { ?>
              <tr><td class="empty" colspan="7">No items in this category yet.</td></tr>
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
                   href="edit_item.php?id=<?= $row['item_id'] ?>&from=index"
                   data-edit="item"
                   data-id="<?= (int)$row['item_id'] ?>"
                   data-category-id="<?= (int)$cat['category_id'] ?>"
                   data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>"
                   data-price="<?= htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8') ?>"
                   data-details="<?= htmlspecialchars($row['details'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   data-from-index="1">Edit</a>
                <a class="btn-del" href="core/handleForms.php?deleteItemId=<?= $row['item_id'] ?>&category_id=<?= $cat['category_id'] ?>&from=index"
                   data-confirm="Delete this item?">Delete</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coffee Shop</title>
  <link rel="stylesheet" href="design_style.css">
</head>
<body>
<div class="container">
  <div class="topbar">
    <h1>&#9749; Coffee Shop &mdash; Hello STAFF, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <a class="logout" href="core/handleForms.php?logoutAUser=1">Logout</a>
  </div>

  <h3>Add Category</h3>
  <form class="add-form" action="core/handleForms.php" method="POST">
    <input type="text" name="name" placeholder="Category name" required>
    <input type="submit" name="insertCategoryBtn" value="Add Category">
  </form>

  <h3>Menu Categories</h3>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Category</th><th>Added by</th><th>Last updated by</th><th>Last updated</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $row) { ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['added_by_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['updated_by_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($row['last_updated']) ?></td>
          <td class="actions">
            <a class="btn-edit"
               href="edit_category.php?id=<?= $row['category_id'] ?>"
               data-edit="category"
               data-id="<?= (int)$row['category_id'] ?>"
               data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>">Edit</a>
            <a class="btn-del" href="core/handleForms.php?deleteCategoryId=<?= $row['category_id'] ?>"
               data-confirm="Delete this category and its items?">Delete</a>
          </td>
        </tr>
        <?php } ?>
        <?php if (count($categories) === 0) { ?>
          <tr><td class="empty" colspan="5">No categories yet. Add one above.</td></tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <?php foreach ($categories as $category) { renderMenuPanel($pdo, $category); } ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="logout.js"></script>
</body>
</html>