<?php
require_once 'models.php';

/* ---------- REGISTER ---------- */
if (isset($_POST['registerUserBtn'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if ($username !== '' && $password !== '') {
        $age = ($_POST['age'] !== '') ? (int)$_POST['age'] : null;
        $ok = insertNewUser($pdo, trim($_POST['first_name']), trim($_POST['last_name']),
                trim($_POST['address']), $age, $username,
                password_hash($password, PASSWORD_DEFAULT));
        header("Location: " . ($ok ? "../login.php" : "../register.php"));
    } else {
        $_SESSION['message'] = "Please fill in all required fields.";
        header("Location: ../register.php");
    }
    exit;
}

/* ---------- LOGIN ---------- */
if (isset($_POST['loginUserBtn'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if ($username !== '' && $password !== '') {
        header("Location: " . (loginUser($pdo, $username, $password) ? "../index.php" : "../login.php"));
    } else {
        $_SESSION['message'] = "Please fill in username and password.";
        header("Location: ../login.php");
    }
    exit;
}

/* ---------- LOGOUT ---------- */
if (isset($_GET['logoutAUser'])) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit;
}

/* ---------- KAILANGAN NAKA-LOGIN SA LAHAT NG SUSUNOD ---------- */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
$uid = $_SESSION['user_id'];

/* ---------- CATEGORIES ---------- */
if (isset($_POST['insertCategoryBtn'])) {
    insertCategory($pdo, trim($_POST['name']), trim($_POST['description']), $uid);
    header("Location: ../index.php"); exit;
}
if (isset($_POST['updateCategoryBtn'])) {
    updateCategory($pdo, $_POST['category_id'], trim($_POST['name']), trim($_POST['description']), $uid);
    header("Location: ../index.php"); exit;
}
if (isset($_GET['deleteCategoryId'])) {
    deleteCategory($pdo, $_GET['deleteCategoryId']);
    header("Location: ../index.php"); exit;
}

/* ---------- MENU ITEMS (COFFEE / PASTRY) ---------- */
if (isset($_POST['insertItemBtn'])) {
    insertItem($pdo, $_POST['category_id'], trim($_POST['name']), $_POST['price'], trim($_POST['details']), $uid);
    header("Location: " . (isset($_POST['from_index'])
        ? "../index.php"
        : "../item.php?category_id=" . $_POST['category_id']));
    exit;
}
if (isset($_POST['updateItemBtn'])) {
    updateItem($pdo, $_POST['item_id'], trim($_POST['name']), $_POST['price'], trim($_POST['details']), $uid);
    header("Location: " . (isset($_POST['from_index'])
        ? "../index.php"
        : "../item.php?category_id=" . $_POST['category_id']));
    exit;
}
if (isset($_GET['deleteItemId'])) {
    deleteItem($pdo, $_GET['deleteItemId']);
    header("Location: " . (isset($_GET['from'])
        ? "../index.php"
        : "../item.php?category_id=" . $_GET['category_id']));
    exit;
}

header("Location: ../index.php");
?>