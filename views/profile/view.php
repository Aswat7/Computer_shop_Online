<?php
// views/profile/view.php
$page_title = 'My Profile';

include __DIR__ . '/../layout/header.php';
?>

<div class="page-wrapper">

    <!-- SUCCESS BANNERS -->
    <?php if ($success === 'profile_updated'): ?>
        <div class="alert alert-success">✅ Profile updated successfully!</div>
    <?php elseif ($success === 'password_changed'): ?>
        <div class="alert alert-success">✅ Password changed successfully!</div>
    <?php endif; ?>

    <div class="profile-wrapper">

        <!-- SIDEBAR -->
        <div class="profile-sidebar">
            <div class="avatar-box">
                <?php if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                <?php else: ?>
                    <div class="avatar-placeholder">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>
                <?php endif; ?>

                <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                <div class="user-role"><?php echo htmlspecialchars($user['role']); ?></div>
            </div>

            <div class="profile-nav">
                <a href="../controllers/HomeController.php">🏠 Home</a>
                <a href="../controllers/ProfileController.php?action=view" class="active">👤 My Profile</a>

                <?php if ($user['role'] === 'admin'): ?>
                    <a href="../controllers/AdminDashboardController.php">⚙️ Admin Panel</a>
                <?php endif; ?>

                <a href="../controllers/AuthController.php?action=logout">🚪 Logout</a>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="profile-main">

            <!-- UPDATE PROFILE SECTION -->
            <div class="profile-section">
                <h3>✏️ Update Profile</h3>

                <form method="POST"
                      action="../controllers/ProfileController.php?action=update"
                      enctype="multipart/form-data"
                      onsubmit="return validateProfileForm()">

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name"
                               value="<?php echo htmlspecialchars($user['name']); ?>">
                        <div class="error-text" id="name-error">
                            <?php echo htmlspecialchars($errors['name'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email"
                               value="<?php echo htmlspecialchars($user['email']); ?>">
                        <div class="error-text" id="email-error">
                            <?php echo htmlspecialchars($errors['email'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Profile Picture -->
                    <div class="form-group">
                        <label for="profile_picture">Profile Picture (JPEG/PNG, max 2MB)</label>
                        <input type="file" id="profile_picture" name="profile_picture"
                               accept=".jpg,.jpeg,.png"
                               onchange="validateProfilePicture(this)">
                        <div class="error-text" id="picture-error">
                            <?php echo htmlspecialchars($errors['picture'] ?? ''); ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" style="width:auto;">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>

            <!-- CHANGE PASSWORD SECTION -->
            <div class="profile-section">
                <h3>🔒 Change Password</h3>

                <form method="POST"
                      action="../controllers/ProfileController.php?action=change_password"
                      onsubmit="return validatePasswordForm()">

                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <input type="password" id="current_password" name="current_password"
                               placeholder="Enter your current password">
                        <div class="error-text" id="current-error">
                            <?php echo htmlspecialchars($errors['current'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="new_password">New Password * (min. 8 characters)</label>
                        <input type="password" id="new_password" name="new_password"
                               placeholder="Enter a new strong password">
                        <div class="error-text" id="new-pass-error">
                            <?php echo htmlspecialchars($errors['new_pass'] ?? ''); ?>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password"
                               placeholder="Re-enter new password">
                        <div class="error-text" id="confirm-error">
                            <?php echo htmlspecialchars($errors['confirm'] ?? ''); ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-secondary" style="width:auto;">
                            Change Password
                        </button>
                    </div>

                </form>
            </div>

            <!-- ACCOUNT INFO -->
            <div class="profile-section">
                <h3>ℹ️ Account Info</h3>
                <p style="font-size:14px;color:#555;line-height:1.7;">
                    <strong>Account Type:</strong> <?php echo ucfirst($user['role']); ?><br>
                    <strong>Member Since:</strong>
                    <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                </p>
            </div>

        </div>
    </div>
</div>

<script src="../public/js/auth.js"></script>

<?php include '../layout/footer.php'; ?>