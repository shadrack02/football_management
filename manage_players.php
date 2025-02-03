<?php
session_start();
include 'db_config.php';

// Ensure only Technical Director can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Technical Director') {
    header("Location: dashboard.php");
    exit();
}

// Handle Add/Edit Player
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $position = $_POST['position'];
    $date_of_birth = $_POST['date_of_birth'];
    $nationality = $_POST['nationality'];
    $jersey_number = $_POST['jersey_number'];
    $status = $_POST['status'];

    if ($id) {
        // Update existing player
        $query = "UPDATE players SET first_name=?, last_name=?, position=?, date_of_birth=?, nationality=?, jersey_number=?, status=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $first_name, $last_name, $position, $date_of_birth, $nationality, $jersey_number, $status, $id);
    } else {
        // Add new player
        $query = "INSERT INTO players (first_name, last_name, position, date_of_birth, nationality, jersey_number, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $first_name, $last_name, $position, $date_of_birth, $nationality, $jersey_number, $status);
    }
    $stmt->execute();
}

// Handle Delete Player
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM players WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Fetch Players
$query = "SELECT * FROM players";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Players</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color:rgb(145, 251, 255); }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color:rgb(99, 242, 247); color: white; }
        .form-container { margin-bottom: 20px; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        .btn { padding: 10px; background-color:rgb(134, 221, 243); color: white; border: none; cursor: pointer; }
        .btn-delete { background-color: #f44336; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Players</h1>
        
        <div class="form-container">
            <h2>Add/Edit Player</h2>
            <form method="POST">
                <input type="hidden" name="id" id="player-id">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="text" name="position" placeholder="Position">
                <input type="date" name="date_of_birth" placeholder="Date of Birth">
                <input type="text" name="nationality" placeholder="Nationality">
                <input type="number" name="jersey_number" placeholder="Jersey Number">
                <select name="status">
                    <option value="Active">Active</option>
                    <option value="Injured">Injured</option>
                    <option value="On Loan">On Loan</option>
                    <option value="Retired">Retired</option>
                </select>
                <button type="submit" class="btn">Save Player</button>
            </form>
        </div>

        <h2>Player List</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Jersey Number</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><?php echo htmlspecialchars($row['jersey_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <button onclick="editPlayer(<?php echo json_encode($row); ?>)" class="btn">Edit</button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn" style="display: inline-block; margin-top: 10px;">Back to Dashboard</a>
    </div>

    <script>
    function editPlayer(player) {
        document.getElementById('player-id').value = player.id;
        document.querySelector('input[name="first_name"]').value = player.first_name;
        document.querySelector('input[name="last_name"]').value = player.last_name;
        document.querySelector('input[name="position"]').value = player.position || '';
        document.querySelector('input[name="date_of_birth"]').value = player.date_of_birth || '';
        document.querySelector('input[name="nationality"]').value = player.nationality || '';
        document.querySelector('input[name="jersey_number"]').value = player.jersey_number || '';
        document.querySelector('select[name="status"]').value = player.status || 'Active';
    }
    </script>
</body>
</html>