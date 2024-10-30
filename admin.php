<?php
session_start();

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "car_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simple authentication (replace with more secure method in production)
if (!isset($_SESSION['admin']) && isset($_POST['password'])) {
    if ($_POST['password'] === 'admin123') {
        $_SESSION['admin'] = true;
    }
}

if (isset($_POST['action']) && $_SESSION['admin']) {
    if ($_POST['action'] === 'add_car') {
        $make = $_POST['make'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $daily_rate = $_POST['daily_rate'];
        
        $sql = "INSERT INTO cars (make, model, year, daily_rate) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssid", $make, $model, $year, $daily_rate);
        $stmt->execute();
    } elseif ($_POST['action'] === 'delete_car') {
        $car_id = $_POST['car_id'];
        
        $sql = "DELETE FROM cars WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
    }
}

$cars = [];
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Car Rental System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.html" class="text-2xl font-bold">Car Rental</a>
            <ul class="flex space-x-4">
                <li><a href="index.html" class="hover:underline">Home</a></li>
                <li><a href="car-listing.php" class="hover:underline">Car Listing</a></li>
                <li><a href="about.html" class="hover:underline">About</a></li>
                <li><a href="faq.html" class="hover:underline">FAQs</a></li>
                <li><a href="contact.html" class="hover:underline">Contact</a></li>
                <li><a href="admin.php" class="hover:underline">Admin</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Admin Panel</h1>
        
        <?php if (!isset($_SESSION['admin'])): ?>
            <form method="post" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Admin Login</h2>
                <input type="password" name="password" placeholder="Enter admin password" class="w-full p-2 mb-4 border rounded">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Login</button>
            </form>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Add New Car</h2>
                <form method="post" class="grid grid-cols-2 gap-4">
                    <input type="hidden" name="action" value="add_car">
                    <input type="text" name="make" placeholder="Make" required class="p-2 border rounded">
                    <input type="text" name="model" placeholder="Model" required class="p-2 border rounded">
                    <input type="number" name="year" placeholder="Year" required class="p-2 border rounded">
                    <input type="number" name="daily_rate" placeholder="Daily Rate" step="0.01" required class="p-2 border rounded">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 col-span-2">Add Car</button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Manage Cars</h2>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="text-left p-2">ID</th>
                            <th class="text-left p-2">Make</th>
                            <th class="text-left p-2">Model</th>
                            <th class="text-left p-2">Year</th>
                            <th class="text-left p-2">Daily Rate</th>
                            <th class="text-left p-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                            <tr>
                                <td class="p-2"><?php echo $car['id']; ?></td>
                                <td class="p-2"><?php echo $car['make']; ?></td>
                                <td class="p-2"><?php echo $car['model']; ?></td>
                                <td class="p-2"><?php echo $car['year']; ?></td>
                                <td class="p-2">$<?php echo $car['daily_rate']; ?></td>
                                <td class="p-2">
                                    <form method="post" onsubmit="return confirm('Are you sure you want to delete this car?');">
                                        <input type="hidden" name="action" value="delete_car">
                                        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>