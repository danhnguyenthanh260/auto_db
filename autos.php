<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "pdo.php";
session_start();

if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    echo '<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>8e72af4b Danh Nguyễn Autos - Error</title>
</head>
<body>
  <p style="color:red;">Name parameter missing</p>
</body>
</html>';
    return;
}

$failure = false;
$success = false;

if (isset($_POST['logout'])) {
    header("Location: index.php");
    return;
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1) {
        $failure = "Make is required";
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $failure = "Mileage and year must be numeric";
    } else {
        $sql = "INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));
        $success = "Record inserted";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>8e72af4b Danh Nguyễn - Autos Tracker</title>
</head>
<body>
<h1>Tracking Autos for <?= htmlentities($_GET['name']) ?></h1>

<?php
if ($failure !== false) {
    echo('<p style="color: red;">' . htmlentities($failure) . "</p>\n");
}
if ($success !== false) {
    echo('<p style="color: green;">' . htmlentities($success) . "</p>\n");
}
?>

<form method="post">
Make: <input type="text" name="make"><br/>
Year: <input type="text" name="year"><br/>
Mileage: <input type="text" name="mileage"><br/>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<ul>
<?php
try {
    $stmt = $pdo->query("SELECT make, year, mileage FROM autos");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlentities($row['year']) . " " . htmlentities($row['make']) . " / " . htmlentities($row['mileage']) . "</li>\n";
    }
} catch (Exception $ex) {
    echo "<p style='color:red;'>🔥 Lỗi PDO: " . htmlentities($ex->getMessage()) . "</p>";
    error_log("🔥 Lỗi PDO chi tiết: " . $ex->getMessage());
}
?>
</ul>
</body>
</html>
