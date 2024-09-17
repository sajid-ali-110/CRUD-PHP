<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<title>CRUD</title>
	<script>
		function showAlert(message) {
			alert(message);
		}
	</script>
</head>
<body>

<?php
	include('./config.php');

	$alertMessage = "";

	// Handle adding new data
	if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['city'])) {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$city = $_POST['city'];

		if (!empty($name) && !empty($email) && !empty($city)) {
			$userrs = $conn->prepare("INSERT INTO users (`name`, `email`, `city`) VALUES (:name, :email, :city)");
			$userrs->bindParam(':name', $name);
			$userrs->bindParam(':email', $email);
			$userrs->bindParam(':city', $city);

			if ($userrs->execute()) {
				$alertMessage = "Record added successfully!";
			} else {
				$alertMessage = "Error adding record.";
			}
		} else {
			$alertMessage = "Please fill in all fields.";
		}
	}

	// Handle deleting a record
	if (isset($_POST['delete'])) {
		$id = $_POST['delete'];
		$delet = $conn->prepare("DELETE FROM users WHERE id = :id");
		$delet->bindParam(":id", $id);
		if ($delet->execute()) {
			$alertMessage = "Record deleted successfully!";
		} else {
			$alertMessage = "Failed to delete record.";
		}
	}

	// Handle updating a record
	if (isset($_POST['update'])) {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$city = $_POST['city'];

		if (!empty($name) && !empty($email) && !empty($city)) {
			$update = $conn->prepare("UPDATE users SET 
				name = :name, 
				email = :email, 
				city = :city 
				WHERE id = :id");
			$update->bindParam(':name', $name);
			$update->bindParam(':email', $email);
			$update->bindParam(':city', $city);
			$update->bindParam(':id', $id);

			if ($update->execute()) {
				$alertMessage = "Record updated successfully!";
			} else {
				$alertMessage = "Failed to update record.";
			}
		} else {
			$alertMessage = "Please fill in all fields.";
		}
	}

	// Fetch the data for display
	$fetch = $conn->prepare("SELECT * FROM users");
	$fetch->execute();
	$users = $fetch->fetchAll(PDO::FETCH_ASSOC);

	// Fetch data for editing
	$editUser = null;
	if (isset($_POST['edit'])) {
		$id = $_POST['edit'];
		$editStmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
		$editStmt->bindParam(':id', $id);
		$editStmt->execute();
		$editUser = $editStmt->fetch(PDO::FETCH_ASSOC);
	}

	// Show alert message if any
	if ($alertMessage) {
		echo "<script>showAlert('". htmlspecialchars($alertMessage) ."');</script>";
	}
?>

<!-- Form to add new data -->
<form action="index.php" method="post" class="formss">
	<label for="fname">Name</label>
	<input type="text" id="fname" name="name" placeholder="Name" value="<?php echo isset($editUser['name']) ? htmlspecialchars($editUser['name']) : ''; ?>">

	<label for="lname">Email</label>
    <input type="text" id="lname" name="email" placeholder="Email" value="<?php echo isset($editUser['email']) ? htmlspecialchars($editUser['email']) : ''; ?>">

	<label for="city">City</label>
	<input type="text" id="city" name="city" placeholder="City" value="<?php echo isset($editUser['city']) ? htmlspecialchars($editUser['city']) : ''; ?>">

	<?php if ($editUser): ?>
		<input type="hidden" name="id" value="<?php echo htmlspecialchars($editUser['id']); ?>">
		<input type="submit" name="update" value="Update">
	<?php else: ?>
		<input type="submit" value="Add">
	<?php endif; ?>
</form>

<h2>Users Table</h2>

<table>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Email</th>
			<th>City</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo htmlspecialchars($user['id']); ?></td>
				<td><?php echo htmlspecialchars($user['name']); ?></td>
				<td><?php echo htmlspecialchars($user['email']); ?></td>
				<td><?php echo htmlspecialchars($user['city']); ?></td>
				<td>
					<form action="index.php" method="post" style="display:inline;">
						<button type="submit" class="button_one" name="edit" value="<?php echo htmlspecialchars($user['id']); ?>">Edit</button>
					</form>
					<form action="index.php" method="post" style="display:inline;">
						<button type="submit" class="button_two" name="delete" value="<?php echo htmlspecialchars($user['id']); ?>">Delete</button>
					</form>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

</body>
</html>
