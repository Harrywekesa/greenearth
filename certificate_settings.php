<?php
include 'php/init.php'; // Start session and initialize 
include 'php/header.php';

// Check if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'php/db.php';

$error_message = '';
$success_message = '';

// Fetch current settings from the database
$settings_sql = "SELECT * FROM certificate_settings LIMIT 1";
$settings_result = $conn->query($settings_sql);

if ($settings_result->num_rows > 0) {
    $settings = $settings_result->fetch_assoc();
} else {
    $settings = [
        'logo_greenearth' => 'images/logo.png',
        'logo_kenya' => 'images/kenya-logo.png',
        'signature_ceo' => 'images/default-signature.png',
        'ceo_name' => 'John Doe'
    ];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle GreenEarth logo upload (optional)
    $logo_greenearth = $settings['logo_greenearth']; // Keep existing logo by default
    if (isset($_FILES['logo_greenearth']) && $_FILES['logo_greenearth']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['logo_greenearth']['tmp_name'];
        $file_name = basename($_FILES['logo_greenearth']['name']);
        $upload_dir = 'images/logos/';
        $new_logo_path = $upload_dir . $file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($tmp_name, $new_logo_path)) {
            // Delete old logo if it exists
            if ($settings['logo_greenearth'] && file_exists($settings['logo_greenearth'])) {
                unlink($settings['logo_greenearth']);
            }

            $logo_greenearth = $new_logo_path; // Update logo path
        } else {
            $error_message = 'Error uploading GreenEarth logo.';
        }
    }

    // Handle Kenya logo upload (optional)
    $logo_kenya = $settings['logo_kenya']; // Keep existing logo by default
    if (isset($_FILES['logo_kenya']) && $_FILES['logo_kenya']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['logo_kenya']['tmp_name'];
        $file_name = basename($_FILES['logo_kenya']['name']);
        $upload_dir = 'images/logos/';
        $new_logo_path = $upload_dir . $file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($tmp_name, $new_logo_path)) {
            // Delete old logo if it exists
            if ($settings['logo_kenya'] && file_exists($settings['logo_kenya'])) {
                unlink($settings['logo_kenya']);
            }

            $logo_kenya = $new_logo_path; // Update logo path
        } else {
            $error_message = 'Error uploading Kenya logo.';
        }
    }

    // Handle CEO signature upload (optional)
    $signature_ceo = $settings['signature_ceo']; // Keep existing signature by default
    if (isset($_FILES['signature_ceo']) && $_FILES['signature_ceo']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['signature_ceo']['tmp_name'];
        $file_name = basename($_FILES['signature_ceo']['name']);
        $upload_dir = 'images/signatures/';
        $new_signature_path = $upload_dir . $file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create directory if it doesn't exist
        }

        if (move_uploaded_file($tmp_name, $new_signature_path)) {
            // Delete old signature if it exists
            if ($settings['signature_ceo'] && file_exists($settings['signature_ceo'])) {
                unlink($settings['signature_ceo']);
            }

            $signature_ceo = $new_signature_path; // Update signature path
        } else {
            $error_message = 'Error uploading CEO signature.';
        }
    }

    // Capture CEO name
    $ceo_name = trim($_POST['ceo_name']) ?? 'John Doe';

    // Update certificate settings in the database
    $update_sql = "INSERT INTO certificate_settings (logo_greenearth, logo_kenya, signature_ceo, ceo_name) 
                  VALUES (?, ?, ?, ?) 
                  ON DUPLICATE KEY UPDATE 
                  logo_greenearth = VALUES(logo_greenearth), 
                  logo_kenya = VALUES(logo_kenya), 
                  signature_ceo = VALUES(signature_ceo), 
                  ceo_name = VALUES(ceo_name)";
    $stmt = $conn->prepare($update_sql);

    if (!$stmt) {
        $error_message = 'Error preparing SQL statement: ' . $conn->error;
    } else {
        $stmt->bind_param("ssss", $logo_greenearth, $logo_kenya, $signature_ceo, $ceo_name);

        if ($stmt->execute()) {
            $success_message = 'Certificate settings updated successfully!';
        } else {
            $error_message = 'Error updating certificate settings: ' . $stmt->error;
        }
    }
}
?>

<section class="certificate-settings">
    <h2>Certificate Settings</h2>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <!-- GreenEarth Logo -->
        <div class="form-column">
            <label for="logo_greenearth">Upload GreenEarth Logo (Optional):</label>
            <input type="file" id="logo_greenearth" name="logo_greenearth" accept="image/*">
            <p>Current Logo: 
                <img src="<?php echo htmlspecialchars($settings['logo_greenearth']); ?>" alt="GreenEarth Logo" style="max-width: 100px; max-height: 50px;">
            </p>
        </div>

        <!-- Kenya Logo -->
        <div class="form-column">
            <label for="logo_kenya">Upload Kenya Logo (Optional):</label>
            <input type="file" id="logo_kenya" name="logo_kenya" accept="image/*">
            <p>Current Logo: 
                <img src="<?php echo htmlspecialchars($settings['logo_kenya']); ?>" alt="Kenya Logo" style="max-width: 100px; max-height: 50px;">
            </p>
        </div>

        <!-- CEO Signature -->
        <div class="form-column">
            <label for="signature_ceo">Upload CEO Signature (Optional):</label>
            <input type="file" id="signature_ceo" name="signature_ceo" accept="image/*">
            <p>Current Signature: 
                <img src="<?php echo htmlspecialchars($settings['signature_ceo']); ?>" alt="CEO Signature" style="max-width: 150px; max-height: 50px;">
            </p>
        </div>

        <!-- CEO Name -->
        <div class="form-column">
            <label for="ceo_name">CEO Name:</label>
            <input type="text" id="ceo_name" name="ceo_name" value="<?php echo htmlspecialchars($settings['ceo_name']); ?>" required>
        </div>

        <!-- Submit Button -->
        <div class="form-actions">
            <button type="submit" class="button">Save Settings</button>
        </div>
    </form>
</section>

<?php include 'php/footer.php'; ?>