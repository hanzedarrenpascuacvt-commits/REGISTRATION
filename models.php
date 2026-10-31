<?php
require_once 'dbConfig.php';

/* ================= USERS ================= */
function insertNewUser($pdo, $first, $last, $address, $age, $username, $hashedPassword) {
    $check = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->rowCount() > 0) {
        $_SESSION['message'] = "User already exists";
        return false;
    }
    $sql = "INSERT INTO users (first_name,last_name,address,age,username,password) VALUES (?,?,?,?,?,?)";
    if ($pdo->prepare($sql)->execute([$first, $last, $address, $age, $username, $hashedPassword])) {
        $_SESSION['success'] = "Congratulations! You now have an account here. Please log in below.";
        return true;
    }
    $_SESSION['message'] = "An error occured from the query";
    return false;
}

function loginUser($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['user_id']  = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        return true;
    }
    $_SESSION['message'] = "Username/password invalid";
    return false;
}

/* ================= CATEGORIES (PARENT) ================= */
function getAllCategories($pdo) {
    $sql = "SELECT c.*, u1.username AS added_by_name, u2.username AS updated_by_name
            FROM categories c
            LEFT JOIN users u1 ON c.added_by = u1.user_id
            LEFT JOIN users u2 ON c.last_updated_by = u2.user_id
            ORDER BY c.category_id DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getCategoryById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCategoryByName($pdo, $name) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1");
    $stmt->execute([$name]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertCategory($pdo, $name, $description, $user_id) {
    $sql = "INSERT INTO categories (name, description, added_by, last_updated_by) VALUES (?,?,?,?)";
    return $pdo->prepare($sql)->execute([$name, $description, $user_id, $user_id]);
}

function updateCategory($pdo, $id, $name, $description, $user_id) {
    $sql = "UPDATE categories SET name=?, description=?, last_updated_by=? WHERE category_id=?";
    return $pdo->prepare($sql)->execute([$name, $description, $user_id, $id]);
}

function deleteCategory($pdo, $id) {
    return $pdo->prepare("DELETE FROM categories WHERE category_id=?")->execute([$id]);
}

/* ================= MENU ITEMS (CHILDREN) ================= */
function getItemsByCategory($pdo, $category_id) {
    $sql = "SELECT m.*, u1.username AS added_by_name, u2.username AS updated_by_name
            FROM menu_items m
            LEFT JOIN users u1 ON m.added_by = u1.user_id
            LEFT JOIN users u2 ON m.last_updated_by = u2.user_id
            WHERE m.category_id = ?
            ORDER BY m.item_id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getItemById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE item_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertItem($pdo, $category_id, $name, $price, $details, $user_id) {
    $sql = "INSERT INTO menu_items (category_id, name, price, details, added_by, last_updated_by) VALUES (?,?,?,?,?,?)";
    return $pdo->prepare($sql)->execute([$category_id, $name, $price, $details, $user_id, $user_id]);
}

function updateItem($pdo, $id, $name, $price, $details, $user_id) {
    $sql = "UPDATE menu_items SET name=?, price=?, details=?, last_updated_by=? WHERE item_id=?";
    return $pdo->prepare($sql)->execute([$name, $price, $details, $user_id, $id]);
}

function deleteItem($pdo, $id) {
    return $pdo->prepare("DELETE FROM menu_items WHERE item_id=?")->execute([$id]);
}
?>