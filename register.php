<?php 

session_start();
include('db.php');

if (isset($_SESSION['username'])) {
    header("Location: chat.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

        // Check if username already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already exists";
        } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: chat.php");
            exit();
        } else {
            $error = "Registration failed";
        }

        }
        $stmt->close();

}
$conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
</head>
<body style="
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 0;
  background: linear-gradient(135deg, #0084ff, #44aaff);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  overflow-x: hidden;
">
  <div style="
    width: 100%;
    max-width: 400px;
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    padding: 30px 25px;
    text-align: center;
  ">
    <h1 style="color: #0084ff; margin-bottom: 25px;">Register</h1>

    <?php if (isset($error)): ?>
      <p style="
        background: #ffe0e0;
        color: #b00020;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: bold;
      "><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" style="display: flex; flex-direction: column; gap: 15px;">
      <label for="username" style="font-weight: bold; text-align: left; color: #333;">Username:</label>
      <input type="text" id="username" name="username" required
        style="padding: 10px 12px; font-size: 16px; border-radius: 8px; border: 1px solid #ccc; transition: border 0.3s;">

      <label for="password" style="font-weight: bold; text-align: left; color: #333;">Password:</label>
      <input type="password" id="password" name="password" required
        style="padding: 10px 12px; font-size: 16px; border-radius: 8px; border: 1px solid #ccc; transition: border 0.3s;">

      <button type="submit"
        style="background-color: #0084ff; color: white; font-size: 16px; padding: 12px; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;"
        onmouseover="this.style.backgroundColor='#0056b3'"
        onmouseout="this.style.backgroundColor='#0084ff'">
        Register
      </button>
    </form>

    <p style="margin-top: 15px; font-size: 14px;">
      Already have an account? 
      <a href="login.php" style="color: #0084ff; text-decoration: none; font-weight: bold;"
         onmouseover="this.style.textDecoration='underline'"
         onmouseout="this.style.textDecoration='none'">
         Login here
      </a>.
    </p>
  </div>
</body>
</html>
