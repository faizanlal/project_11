<?php
session_start();

// Initialize users array if not already
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

$errors = [];
$firstName = $lastName = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validation
    if (empty($firstName) || strlen($firstName) < 2) $errors[] = "First name required";
    if (empty($lastName) || strlen($lastName) < 2) $errors[] = "Last name required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required";
    if (empty($password) || strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match";
    if (isset($_SESSION['users'][$email])) $errors[] = "Email already registered";

    if (!$errors) {
        $_SESSION['users'][$email] = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        $_SESSION['registration_success'] = "Signup successful! Please login.";
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .signup-container {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border 0.3s;
        }

        input:focus {
            border-color: #2575fc;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(135deg, #2575fc 0%, #6a11cb 100%);
            transform: translateY(-2px);
        }

        p {
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }

        a {
            color: #2575fc;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-msg {
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="signup-container">
    <h2>Sign Up</h2>

    <?php if ($errors): ?>
        <div class="error-msg">
            <ul>
                <?php foreach ($errors as $err) echo "<li>$err</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="signup.php">
        <input type="text" name="firstName" placeholder="First Name" value="<?=htmlspecialchars($firstName)?>" required>
        <input type="text" name="lastName" placeholder="Last Name" value="<?=htmlspecialchars($lastName)?>" required>
        <input type="email" name="email" placeholder="Email" value="<?=htmlspecialchars($email)?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
