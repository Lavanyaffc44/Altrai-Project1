<?php
session_start();

// Database configuration (replace with your database credentials)
$host = "localhost";
$username = "lavanya.altrai@gmail.com";
$password = "Altrai@123!";
$database = "loginpagekey";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to create a secure database connection
function createDatabaseConnection() {
    global $host, $username, $password, $database;
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

// Function to safely hash a password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify a password
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Validate and sanitize user input (you should implement input validation)
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Create a database connection
        $conn = createDatabaseConnection();

        // Hash the password before storing it in the database
        $hashedPassword = hashPassword($password);

        // Insert the user into the database (you should use prepared statements)
        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashedPassword')";
        if (mysqli_query($conn, $sql)) {
            echo "Registration successful.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    } elseif (isset($_POST["login"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Validate and sanitize user input (you should implement input validation)
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Create a database connection
        $conn = createDatabaseConnection();

        // Query the database for the user
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify the password
            if (verifyPassword($password, $user["password"])) {
                $_SESSION["user"] = $user["email"];
                header("Location: welcome.php"); // Redirect to a welcome page
            } else {
                echo "Login failed. Invalid email or password.";
            }
        } else {
            echo "Login failed. Invalid email or password.";
        }

        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title>Responsive Login Form | CodingLab</title>
    <link rel="stylesheet" href="index.css">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <div class="container">
      <form action="#" method="POST">
        <div class="title">Login</div>
        <?php if (isset($error_message)) { ?>
          <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>
        <div class="input-box underline">
          <input type="text" name="email" placeholder="Enter Your Email" required>
          <div class="underline"></div>
        </div>
        <div class="input-box">
          <input type="password" name="password" placeholder="Enter Your Password" required>
          <div class="underline"></div>
        </div>
        <div class="input-box button">
          <input type="submit" name="login" value="Continue">
        </div>
      </form>
      <div class="option">or Connect With Social Media</div>
      <div class="twitter">
        <a href="#"><i class="fab fa-twitter"></i>Sign in With Twitter</a>
      </div>
      <div class="facebook">
        <a href="#"><i class="fab fa-facebook-f"></i>Sign in With Facebook</a>
      </div>
    </div>
  </body>
</html>
