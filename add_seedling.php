<?php
include 'php/init.php'; // Start session and initialize configurations

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$error_message = '';
$success_message = '';
$image_preview = ''; // Variable to store image preview

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = isset($_POST['price']) ? (float)$_POST['price'] : null;
    $region = trim($_POST['region']);
    $height = trim($_POST['height']) ?? 'tall'; // Default value: tall
    $fruit = trim($_POST['fruit']) ?? 'non-edible'; // Default value: non-edible
    $purpose = trim($_POST['purpose']) ?? 'shade'; // Default value: shade
    $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $upload_dir = 'images/seedlings/';
        $image_path = $upload_dir . $file_name;

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        // Move uploaded file
        if (move_uploaded_file($tmp_name, $image_path)) {
            echo "<p>Image uploaded successfully!</p>";

            // Insert seedling into the database
            $insert_sql = "INSERT INTO seedlings (name, description, price, image, region, height, fruit, purpose, stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);

            if (!$stmt) {
                $error_message = 'Error preparing SQL statement: ' . $conn->error;
            } else {
                // Bind parameters
                $stmt->bind_param("ssdsssssi", $name, $description, $price, $image_path, $region, $height, $fruit, $purpose, $stock);

                // Execute query
                if ($stmt->execute()) {
                    $success_message = 'Seedling added successfully!';
                    header("Refresh:2; url=admin.php"); // Redirect after success
                    exit;
                } else {
                    $error_message = 'Error adding seedling: ' . $stmt->error;
                }
            }
        } else {
            $error_message = 'Error moving uploaded image.';
        }
    } else {
        $error_message = 'Please upload a valid image.';
    }
}
?>

<section class="add-seedling">
    <h2>Add New Seedling</h2>

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
            <input type="text" id="name" name="name" placeholder="Enter seedling name" required>
        </div>

        <!-- Description (Rich Text Editor) -->
        <div class="form-column">
            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Enter seedling description" required></textarea>
        </div>

        <!-- Price -->
        <div class="form-column">
            <label for="price">Price (KES):</label>
            <input type="number" id="price" name="price" step="0.01" min="0" placeholder="Enter price" required>
        </div>

        <!-- Image Upload -->
        <div class="form-column">
            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            <div id="image-preview" style="margin-top: 10px;">
                <?php if (!empty($image_preview)): ?>
                    <img src="<?php echo htmlspecialchars($image_preview); ?>" alt="Preview" style="max-width: 100px; max-height: 100px;">
                <?php endif; ?>
            </div>
        </div>

        <!-- Region -->
        <div class="form-column">
            <label for="region">Region:</label>
            <select id="region" name="region" required>
                <option value="Central Highlands">Central Highlands</option>
                <option value="Lake Victoria Basin and Highlands">Lake Victoria Basin and Highlands</option>
                <option value="North-western">North-western</option>
                <option value="North-eastern">North-eastern</option>
                <option value="South-eastern">South-eastern</option>
                <option value="The Coastal strip">The Coastal strip</option>
                <option value="Rift Valley">Rift Valley</option>
                <option value="East African Rift valley">East African Rift valley</option>
                <option value="Kerio valley">Kerio valley</option>
                <option value="Elegeyo valley">Elegeyo valley</option>
                <option value="Lower Kerio and Suguta valleys">Lower Kerio and Suguta valleys</option>
            </select>
        </div>

        <!-- Height -->
        <div class="form-column">
            <label for="height">Height:</label>
            <select id="height" name="height">
                <option value="tall" selected>Tall</option>
                <option value="short">Short</option>
            </select>
        </div>

        <!-- Fruit -->
        <div class="form-column">
            <label for="fruit">Fruiting:</label>
            <select id="fruit" name="fruit">
                <option value="edible">Edible</option>
                <option value="non-edible" selected>Non-Edible</option>
            </select>
        </div>

        <!-- Purpose -->
        <div class="form-column">
            <label for="purpose">Purpose:</label>
            <select id="purpose" name="purpose">
                <option value="ornamental">Ornamental</option>
                <option value="timber">Timber</option>
                <option value="shade" selected>Shade</option>
            </select>
        </div>

        <!-- Stock -->
        <div class="form-column">
            <label for="stock">Number in Stock:</label>
            <input type="number" id="stock" name="stock" min="0" placeholder="Enter number in stock" required>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
            <button type="submit" class="button">Add Seedling</button>
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
                const previewDiv = document.getElementById('image-preview');
                previewDiv.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="max-width: 100px; max-height: 100px;">';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-preview').innerHTML = '';
        }
    });
</script>

<?php include 'php/footer.php'; ?>