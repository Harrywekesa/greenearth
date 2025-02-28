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
    echo '<p class="error-message">Invalid seedling ID.</p>';
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
    echo '<p class="error-message">Seedling not found.</p>';
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = isset($_POST['price']) ? (float)$_POST['price'] : null;
    $region = trim($_POST['region']);
    $height = trim($_POST['height']);
    $fruit = trim($_POST['fruit']);
    $purpose = trim($_POST['purpose']);
    $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;

    // Handle image upload (optional)
    $image_path = $seedling['image']; // Keep existing image by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $upload_dir = 'images/seedlings/';
        $new_image_path = $upload_dir . $file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($tmp_name, $new_image_path)) {
            // Delete old image if it exists
            if ($seedling['image'] && file_exists($seedling['image'])) {
                unlink($seedling['image']);
            }

            $image_path = $new_image_path; // Update image path
        } else {
            $error_message = 'Error uploading new image.';
        }
    }

    // Update seedling details in the database
    $update_sql = "UPDATE seedlings SET name = ?, description = ?, price = ?, image = ?, region = ?, height = ?, fruit = ?, purpose = ?, stock = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);

    if (!$stmt) {
        $error_message = 'Error preparing SQL statement: ' . $conn->error;
    } else {
        $stmt->bind_param("ssdssssssi", $name, $description, $price, $image_path, $region, $height, $fruit, $purpose, $stock, $seedling_id);

        if ($stmt->execute()) {
            $success_message = 'Seedling updated successfully!';
            header("Refresh:2; url=admin.php"); // Redirect after success
            exit;
        } else {
            $error_message = 'Error updating seedling: ' . $stmt->error;
        }
    }
}
?>

<section class="edit-seedling">
    <h2>Edit Seedling</h2>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" id="seedling-form">
        <!-- Name -->
        <div class="form-column">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($seedling['name']); ?>" required>
        </div>

        <!-- Description (Rich Text Editor) -->
        <div class="form-column">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($seedling['description']); ?></textarea>
        </div>

        <!-- Price -->
        <div class="form-column">
            <label for="price">Price (KES):</label>
            <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($seedling['price']); ?>" required>
        </div>

        <!-- Region -->
        <div class="form-column">
            <label for="region">Region:</label>
            <select id="region" name="region" required>
                <option value="Central Highlands" <?php echo ($seedling['region'] == 'Central Highlands') ? 'selected' : ''; ?>>Central Highlands</option>
                <option value="Lake Victoria Basin and Highlands" <?php echo ($seedling['region'] == 'Lake Victoria Basin and Highlands') ? 'selected' : ''; ?>>Lake Victoria Basin and Highlands</option>
                <option value="North-western" <?php echo ($seedling['region'] == 'North-western') ? 'selected' : ''; ?>>North-western</option>
                <option value="North-eastern" <?php echo ($seedling['region'] == 'North-eastern') ? 'selected' : ''; ?>>North-eastern</option>
                <option value="South-eastern" <?php echo ($seedling['region'] == 'South-eastern') ? 'selected' : ''; ?>>South-eastern</option>
                <option value="The Coastal strip" <?php echo ($seedling['region'] == 'The Coastal strip') ? 'selected' : ''; ?>>The Coastal strip</option>
                <option value="Rift Valley" <?php echo ($seedling['region'] == 'Rift Valley') ? 'selected' : ''; ?>>Rift Valley</option>
                <option value="East African Rift valley" <?php echo ($seedling['region'] == 'East African Rift valley') ? 'selected' : ''; ?>>East African Rift valley</option>
                <option value="Kerio valley" <?php echo ($seedling['region'] == 'Kerio valley') ? 'selected' : ''; ?>>Kerio valley</option>
                <option value="Elegeyo valley" <?php echo ($seedling['region'] == 'Elegeyo valley') ? 'selected' : ''; ?>>Elegeyo valley</option>
                <option value="Lower Kerio and Suguta valleys" <?php echo ($seedling['region'] == 'Lower Kerio and Suguta valleys') ? 'selected' : ''; ?>>Lower Kerio and Suguta valleys</option>
            </select>
        </div>

        <!-- Height -->
        <div class="form-column">
            <label for="height">Height:</label>
            <select id="height" name="height" required>
                <option value="tall" <?php echo ($seedling['height'] == 'tall') ? 'selected' : ''; ?>>Tall</option>
                <option value="short" <?php echo ($seedling['height'] == 'short') ? 'selected' : ''; ?>>Short</option>
            </select>
        </div>

        <!-- Fruit -->
        <div class="form-column">
            <label for="fruit">Fruiting:</label>
            <select id="fruit" name="fruit" required>
                <option value="edible" <?php echo ($seedling['fruit'] == 'edible') ? 'selected' : ''; ?>>Edible</option>
                <option value="non-edible" <?php echo ($seedling['fruit'] == 'non-edible') ? 'selected' : ''; ?>>Non-Edible</option>
            </select>
        </div>

        <!-- Purpose -->
        <div class="form-column">
            <label for="purpose">Purpose:</label>
            <select id="purpose" name="purpose" required>
                <option value="ornamental" <?php echo ($seedling['purpose'] == 'ornamental') ? 'selected' : ''; ?>>Ornamental</option>
                <option value="timber" <?php echo ($seedling['purpose'] == 'timber') ? 'selected' : ''; ?>>Timber</option>
                <option value="shade" <?php echo ($seedling['purpose'] == 'shade') ? 'selected' : ''; ?>>Shade</option>
            </select>
        </div>

        <!-- Stock -->
        <div class="form-column">
            <label for="stock">Number in Stock:</label>
            <input type="number" id="stock" name="stock" min="0" value="<?php echo htmlspecialchars($seedling['stock'] ?? 0); ?>" required>
        </div>

        <!-- Image Upload -->
        <div class="form-column">
            <label for="image">Upload New Image (Optional):</label>
            <input type="file" id="image" name="image" accept="image/*">
            <p>Current Image: 
                <img src="<?php echo htmlspecialchars($seedling['image'] ?? 'images/default-tree.jpg'); ?>" alt="<?php echo htmlspecialchars($seedling['name']); ?>" style="max-width: 100px; max-height: 100px;">
            </p>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
            <button type="submit" class="button">Update Seedling</button>
        </div>
    </form>
</section>

<!-- Include TinyMCE for Rich Text Editing -->
<script src="https://cdn.tiny.cloud/1/55w2doib0zah7klxck5dr4nvmik36cc3rmrnmtxuhbl51z96/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description', // Target the description textarea
        plugins: 'lists link image code',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | numlist bullist | link image',
        menubar: false,
        statusbar: false,
        height: 200,
    });

    // Image Preview Functionality
    document.getElementById('image').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewDiv = document.querySelector('.form-column img');
                if (previewDiv) {
                    previewDiv.src = e.target.result; // Update preview image
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php include 'php/footer.php'; ?>