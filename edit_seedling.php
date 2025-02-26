<?php
include 'php/init.php'; // Start session and initialize configurations
include 'php/header.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$seedling_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($seedling_id <= 0) {
    echo '<p>Invalid seedling ID.</p>';
    exit;
}

// Fetch seedling details
$sql = "SELECT * FROM seedlings WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seedling_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $seedling = $result->fetch_assoc();
} else {
    echo '<p>Seedling not found.</p>';
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $region = trim($_POST['region']);
    $height = trim($_POST['height']);
    $fruit = trim($_POST['fruit']);
    $purpose = trim($_POST['purpose']);

    if (empty($name) || empty($description) || empty($price) || empty($region) || empty($height) || empty($fruit) || empty($purpose)) {
        $error_message = 'All fields are required!';
    } else {
        // Update seedling details
        $sql = "UPDATE seedlings SET name = ?, description = ?, price = ?, region = ?, height = ?, fruit = ?, purpose = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsdssi", $name, $description, $price, $region, $height, $fruit, $purpose, $seedling_id);

        if ($stmt->execute()) {
            $success_message = 'Seedling updated successfully!';
        } else {
            $error_message = 'Error updating seedling: ' . htmlspecialchars($stmt->error);
        }
    }
}

echo '<section class="edit-seedling">';
echo '<h2>Edit Seedling</h2>';

if (!empty($error_message)) {
    echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
}

if (!empty($success_message)) {
    echo '<p style="color: green;">' . htmlspecialchars($success_message) . '</p>';
}

echo '<form method="POST">';
echo '<label for="name">Name:</label>';
echo '<input type="text" id="name" name="name" value="' . htmlspecialchars($seedling['name'] ?? '') . '" required>';

echo '<label for="description">Description:</label>';
echo '<textarea id="description" name="description" required>' . htmlspecialchars($seedling['description'] ?? '') . '</textarea>';

echo '<label for="price">Price:</label>';
echo '<input type="number" id="price" name="price" value="' . htmlspecialchars($seedling['price'] ?? '') . '" step="0.01" required>';

echo '<label for="region">Region:</label>';
echo '<select id="region" name="region" required>';
echo '<option value="nairobi" ' . (($seedling['region'] ?? '') === 'nairobi' ? 'selected' : '') . '>Nairobi</option>';
echo '<option value="kitale" ' . (($seedling['region'] ?? '') === 'kitale' ? 'selected' : '') . '>Kitale</option>';
echo '<option value="eldoret" ' . (($seedling['region'] ?? '') === 'eldoret' ? 'selected' : '') . '>Eldoret</option>';
echo '</select>';

echo '<label for="height">Tree Height:</label>';
echo '<select id="height" name="height" required>';
echo '<option value="tall" ' . (($seedling['height'] ?? '') === 'tall' ? 'selected' : '') . '>Tall Trees</option>';
echo '<option value="short" ' . (($seedling['height'] ?? '') === 'short' ? 'selected' : '') . '>Short Trees</option>';
echo '</select>';

echo '<label for="fruit">Fruiting Trees:</label>';
echo '<select id="fruit" name="fruit" required>';
echo '<option value="edible" ' . (($seedling['fruit'] ?? '') === 'edible' ? 'selected' : '') . '>Edible Fruiting Trees</option>';
echo '<option value="non-edible" ' . (($seedling['fruit'] ?? '') === 'non-edible' ? 'selected' : '') . '>Non-Edible Trees</option>';
echo '</select>';

echo '<label for="purpose">Purpose:</label>';
echo '<select id="purpose" name="purpose" required>';
echo '<option value="ornamental" ' . (($seedling['purpose'] ?? '') === 'ornamental' ? 'selected' : '') . '>Ornamental</option>';
echo '<option value="timber" ' . (($seedling['purpose'] ?? '') === 'timber' ? 'selected' : '') . '>Timber</option>';
echo '<option value="shade" ' . (($seedling['purpose'] ?? '') === 'shade' ? 'selected' : '') . '>Shade</option>';
echo '</select>';

echo '<button type="submit">Update Seedling</button>';
echo '</form>';
echo '</section>';

include 'php/footer.php'; ?>