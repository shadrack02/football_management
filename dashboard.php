<?php
session_start();
include 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Football Team Management Dashboard</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color:rgb(210, 227, 252); 
        }
        .dashboard {
            max-width: 800px;
            margin: 20px auto;
            background-color: paleturquoise;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .menu-item {
            display: block;
            padding: 10px;
            margin: 10px 0;
            background-color:rgb(117, 226, 245);
            color: white;
            text-decoration: none;
            text-align: center;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Football Team Management Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?> (<?php echo $_SESSION['role']; ?>)</p>
        
        <?php if($_SESSION['role'] == 'Technical Director'): ?>
            <a href="manage_players.php" class="menu-item">Manage Players</a>
            <a href="manage_technical_team.php" class="menu-item">Manage Technical Team</a>
        <?php else: ?>
            <a href="view_players.php" class="menu-item">View Players</a>
            <a href="view_technical_team.php" class="menu-item">View Technical Team</a>
        <?php endif; ?>
        
        <a href="logout.php" class="menu-item" style="background-color: #f44336;">Logout</a>
    </div>
</body>
</html>