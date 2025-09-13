<?php
// login.php
session_start();
require_once 'config/config.php';
require_once 'includes/functions.php';

// If the user is already logged in, redirect them
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: user/index.php");
    }
    exit;
}

$errors = [
    'email' => '',
    'password' => '',
    'general' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email)) {
        $errors['email'] = "The 'E-mail' field is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors['password'] = "The 'Password' field is required.";
    }

    if (empty($errors['email']) && empty($errors['password'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['first_name'];
            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: user/index.php");
            }
            exit;
        } else {
            $errors['general'] = "Invalid email or password.";
        }
    }
}

$pageTitle = "Login";
$customCss = "login.css";
require_once 'includes/header.php';
?>
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="login-card">
        <div class="login-title">Login Panel</div>
        <h2 class="mb-4 text-center">Log In</h2>
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $errors['general']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="" method="post" class="needs-validation" novalidate>
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
                    <div class="form-text">Enter your password.</div>
                <?php endif; ?>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-login">Log In</button>
            </div>
        </form>
        <div class="link-register">
            <a href="register.php" class="btn btn-link">Don't have an account? Sign Up</a>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>
