<?php
// Database connection details
$servername = "localhost";
$username = "actual_username"; // Replace with your MySQL username
$password = "actual_password"; // Replace with your MySQL password
$dbname = "car_rental"; // Make sure this is the correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select available cars
$sql = "SELECT * FROM cars WHERE available = 1";
$result = $conn->query($sql);

// Initialize array to store available cars
$cars = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Listing - Car Rental System</title>
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
        <h1 class="text-3xl font-bold mb-4">Available Cars</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($cars as $car): ?>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></h2>
                    <p class="text-gray-600">Year: <?php echo htmlspecialchars($car['year']); ?></p>
                    <p class="text-gray-600">Daily Rate: $<?php echo htmlspecialchars($car['daily_rate']); ?></p>
                    <button onclick="rentCar(<?php echo htmlspecialchars($car['id']); ?>)" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Rent Now</button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function rentCar(carId) {
            const startDate = prompt('Enter start date (YYYY-MM-DD):');
            const endDate = prompt('Enter end date (YYYY-MM-DD):');

            if (startDate && endDate) {
                const formData = new FormData();
                formData.append('car_id', carId);
                formData.append('start_date', startDate);
                formData.append('end_date', endDate);

                fetch('rent_car.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Car rented successfully!');
                        location.reload(); // Refresh the page to update availability
                    } else {
                        alert('Failed to rent car: ' + result.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>
