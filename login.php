<?php
session_start();

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Enter a valid email";
    } elseif (!isset($_SESSION['users'][$email]) || !password_verify($password, $_SESSION['users'][$email]['password'])) {
        $error = "Invalid email or password";
    } else {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_email'] = $email;
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Background gradient and centering */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container styling */
        .login-container {
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

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border 0.3s;
        }

        input[type="email"]:focus, input[type="password"]:focus {
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
            text-align: center;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .success-msg {
            text-align: center;
            color: #2ecc71;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>

    <?php if ($error) echo "<p class='error-msg'>$error</p>"; ?>
    <?php if (!empty($_SESSION['registration_success'])) {
        echo "<p class='success-msg'>".$_SESSION['registration_success']."</p>";
        unset($_SESSION['registration_success']);
    } ?>

    <form method="post" action="login.php">
        <input type="email" name="email" placeholder="Email" value="<?=htmlspecialchars($email)?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</div>
</body>
</html>
