<?php
// register.php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';
require_once 'includes/messages.php';
require_once 'includes/validation.php';

$errors = [
    'first_name' => '',
    'last_name'  => '',
    'email'      => '',
    'password'   => '',
    'password2'  => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    // First name validation
    if (empty($firstName)) {
        $errors['first_name'] = "The 'First Name' field is required.";
    } elseif (!preg_match("/^[a-zA-ZąĄćĆęĘłŁńŃóÓśŚżŻźŹ\-]+$/u", $firstName)) {
        $errors['first_name'] = "The first name contains invalid characters (letters and hyphens only).";
    } elseif (!validateText($firstName)) {
        $errors['first_name'] = "The first name cannot be empty.";
    }

    // Last name validation
    if (empty($lastName)) {
        $errors['last_name'] = "The 'Last Name' field is required.";
    } elseif (!preg_match("/^[a-zA-ZąĄćĆęĘłŁńŃóÓśŚżŻźŹ\-]+$/u", $lastName)) {
        $errors['last_name'] = "The last name contains invalid characters (letters and hyphens only).";
    } elseif (!validateText($lastName)) {
        $errors['last_name'] = "The last name cannot be empty.";
    }

    // Email validation
    if (empty($email)) {
        $errors['email'] = "The 'E-mail' field is required.";
    } elseif (!validateEmail($email)) {
        $errors['email'] = "Invalid email format.";
    }

    // Password validation
    if (empty($password)) {
        $errors['password'] = "The 'Password' field is required.";
    } elseif (!validatePassword($password)) {
        $errors['password'] = "The password must be at least 8 characters.";
    }

    // Password confirmation validation
    if (empty($password2)) {
        $errors['password2'] = "Password confirmation is required.";
    } elseif ($password !== $password2) {
        $errors['password2'] = "Passwords do not match.";
    }

    // Check if the email is unique
    if (empty($errors['email'])) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = "This email is already registered.";
        }
    }

    $hasErrors = array_filter($errors);
    if (!$hasErrors) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:fn, :ln, :em, :pw)");
        $stmt->execute([
            'fn' => $firstName,
            'ln' => $lastName,
            'em' => $email,
            'pw' => $hashedPassword
        ]);
        setMessage("success", "Registration was successful! You can now log in.");
        header("Location: login.php");
        exit;
    }
}

$pageTitle = "Registration";
$customCss = "register.css";
require_once 'includes/header.php';
?>
<div class="register-container">
    <div class="register-card">
        <div class="register-title">Register</div>
        <?php displayMessages(); ?>
        <form action="" method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" name="first_name" id="first_name"
                       class="form-control <?php echo (!empty($errors['first_name'])) ? 'is-invalid' : ''; ?>"
                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                <?php if (!empty($errors['first_name'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                <?php else: ?>
                    <div class="form-text">Enter your first name.</div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" name="last_name" id="last_name"
                       class="form-control <?php echo (!empty($errors['last_name'])) ? 'is-invalid' : ''; ?>"
                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                <?php if (!empty($errors['last_name'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                <?php else: ?>
                    <div class="form-text">Enter your last name.</div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email"
                       class="form-control <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                <?php if (!empty($errors['email'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                <?php else: ?>
                    <div class="form-text">Enter your email address.</div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                       class="form-control <?php echo (!empty($errors['password'])) ? 'is-invalid' : ''; ?>" required>
                <?php if (!empty($errors['password'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                <?php else: ?>
                    <div class="form-text">Choose a strong password (min. 8 characters).</div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">Confirm Password</label>
                <input type="password" name="password2" id="password2"
                       class="form-control <?php echo (!empty($errors['password2'])) ? 'is-invalid' : ''; ?>" required>
                <?php if (!empty($errors['password2'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['password2']; ?></div>
                <?php else: ?>
                    <div class="form-text">Confirm your password.</div>
                <?php endif; ?>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-register">Register</button>
            </div>
        </form>
        <div class="link-login">
            <a href="login.php" class="btn btn-link">Already have an account? Log in</a>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
