<?php
session_start();
include 'db_config.php';

// Ensure only Technical Director can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Technical Director') {
    header("Location: dashboard.php");
    exit();
}

// Handle Add/Edit Technical Team Member
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];

    if ($id) {
        // Update existing member
        $query = "UPDATE technical_team SET first_name=?, last_name=?, role=?, contact_number=?, email=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $first_name, $last_name, $role, $contact_number, $email, $id);
    } else {
        // Add new member
        $query = "INSERT INTO technical_team (first_name, last_name, role, contact_number, email) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $first_name, $last_name, $role, $contact_number, $email);
    }
    $stmt->execute();
}

// Handle Delete Technical Team Member
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM technical_team WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Fetch Technical Team Members
$query = "SELECT * FROM technical_team";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Technical Team</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color:rgb(118, 216, 255); }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color:rgb(65, 236, 241); color: white; }
        .form-container { margin-bottom: 20px; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; }
        .btn { padding: 10px; background-color:rgb(66, 224, 245); color: white; border: none; cursor: pointer; }
        .btn-delete { background-color: #f44336; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Technical Team</h1>
        
        <div class="form-container">
            <h2>Add/Edit Technical Team Member</h2>
            <form method="POST">
                <input type="hidden" name="id" id="member-id">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="Coach">Coach</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Physiotherapist">Physiotherapist</option>
                </select>
                <input type="text" name="contact_number" placeholder="Contact Number">
                <input type="email" name="email" placeholder="Email">
                <button type="submit" class="btn">Save Team Member</button>
            </form>
        </div>

        <h2>Technical Team List</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Contact Number</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <button onclick="editMember(<?php echo json_encode($row); ?>)" class="btn">Edit</button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn" style="display: inline-block; margin-top: 10px;">Back to Dashboard</a>
    </div>

    <script>
    function editMember(member) {
        document.getElementById('member-id').value = member.id;
        document.querySelector('input[name="first_name"]').value = member.first_name;
        document.querySelector('input[name="last_name"]').value = member.last_name;
        document.querySelector('select[name="role"]').value = member.role;
        document.querySelector('input[name="contact_number"]').value = member.contact_number || '';
        document.querySelector('input[name="email"]').value = member.email || '';
    }
    </script>
</body>
</html>