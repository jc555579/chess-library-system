<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="css-login/styles-login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div id="header">
        <h3>Chess Library Management System</h3>
    </div>

    <section id="main-section">
        <div class="image-container">
            <img src="img-login/main-img-login.jpg" alt="Login Image">
            <div class="overlay"></div>
        </div>

        <div>
            <h2 class="login-text">Login</h2>
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
                <div class="row-one-field">
                    <label class="admin-member-label">Please select admin or member</label>
                    <input type="radio" name="login-option" value="Admin" class="admin-radio" style="height:20px; width:20px; vertical-align: middle;">
                    <label class="label-option">Admin</label>

                    <input type="radio" name="login-option" value="Member" class="member-radio" style="height:20px; width:20px; vertical-align: middle;">
                    <label class="label-option">Member</label>
                </div>
                <div class="row-two-field">
                    <label>Username: </label>
                    <input type="text" name="username">

                    <label>PASSWORD</label>
                    <input type="password" name="password">
                    
                    <input type="submit" name="login" value="Login" id="submit">
                </div>
            </form>
        </div>
    </section>

</body>
</html>

<?php
    if (isset($_POST["login"])) { 
        $username = $_POST["username"];
        $password = $_POST["password"];
        $role = $_POST["login-option"]; // Getting role from radio input
        
        // Prepare SQL statement to prevent SQL injection
        $stmt = $connect->prepare("SELECT username, password, role FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verify the hashed password
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $row["username"];
                $_SESSION["role"] = $row["role"];

                // Redirect based on role
                if ($row["role"] === "admin") {
                    header("Location: admin_dashboard.php");
                } else if ($row["role"] === "member") {
                    header("Location: member_dashboard.php");
                } else {
                    echo "❌ Unknown role!";
                }
                exit();
            } else {
                echo "❌ Incorrect password!";
            }
        } else {
            echo "❌ Login failed. Check your username, password, or role.";
        }

        $stmt->close();
    }
?>