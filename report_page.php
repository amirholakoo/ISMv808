<!-- report_page.php -->
echo "Original Date: " . $row["Date"] . "<br>"; // Debugging: Check original date
    $jalaliDate = jdate("Y/m/d", strtotime($row["Date"])); // Convert to Jalali
    echo "Jalali Date: " . $jalaliDate . "<br>"; // Debugging: Check Jalali date
    
<?php

ini_set('display_errors', 1);

error_reporting(E_ALL);

include 'connect_db.php'; // Include the database connection
include 'jdf.php';

// Section 1: Shipments Data
$shipmentsQuery = "SELECT * FROM Shipments WHERE Status IN ('Incoming', 'Outgoing')";
$shipmentsResult = $conn->query($shipmentsQuery);

// Section 2: Truck Data
$trucksQuery = "SELECT LicenseNumber, Location,  Weight1, Weight2 FROM Shipments WHERE Status IN ('Incoming', 'Outgoing')";
$trucksResult = $conn->query($trucksQuery);

// Section 3: In-Stock Products Data
$inStockProductsQuery = "SELECT ReelNumber, Width, Breaks, Location, Status FROM Products WHERE Status = 'In-Stock'";
$inStockProductsResult = $conn->query($inStockProductsQuery);

// Section 4: Recent Sales Orders
$recentSalesQuery = "SELECT * FROM Sales ORDER BY Date DESC LIMIT 10";
$recentSalesResult = $conn->query($recentSalesQuery);

// Section 5: Recent Purchases
$recentPurchasesQuery = "SELECT * FROM Purchases ORDER BY Date DESC LIMIT 10";
$recentPurchasesResult = $conn->query($recentPurchasesQuery);

// Section 6: Alerts and Notices
$alerts = "Check for any low stock or other important notices here.";



// Start the HTML
echo "<!DOCTYPE html>
<html>
<head>
    <title>Report Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        h1 {
            color: #444;
        }

        h2 {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th {
            height: 50px;
            background-color: #f0f0f0;
            color: #333;
        }

        td {
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        tr:nth-child(even) {background-color: #f2f2f2;}

        .container {
            width: 95%;
            margin: auto;
            overflow: hidden;
        }

        .alert {
            padding: 20px;
            background-color: #ff9800;
            color: white;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Report Page</h1>

    // Section 1: Shipments Data
    echo "<h2>Shipments Data</h2>";
    if ($shipmentsResult->num_rows > 0) {
        echo "<table><tr><th>ShipmentID</th><th>Status</th><th>Location</th><th>Loaded Weight</th><th>Unloaded Weight</th></tr>";
        while($row = $shipmentsResult->fetch_assoc()) {
            echo "<tr><td>" . $row["LicenseNumber"] . "</td><td>" . $row["Status"] . "</td><td>" . $row["Location"] . "</td><td>" . $row["Weight1"] . "</td><td>" . $row["Weight2"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No Shipments Data Available<br>";
    }

    // Section 2: Truck Data
    echo "<h2>Truck Data</h2>";
    if ($trucksResult->num_rows > 0) {
        echo "<table><tr><th>License Number</th><th>Location</th><th>Weight 1</th><th>Weight 2</th></tr>";
        while($row = $trucksResult->fetch_assoc()) {
            echo "<tr><td>" . $row["LicenseNumber"] . "</td><td>" . $row["Location"] . "</td><td>" . $row["Weight1"] . "</td><td>" . $row["Weight2"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No Truck Data Available<br>";
    }

    // Section 3: In-Stock Products Data
    echo "<h2>In-Stock Reel Numbers</h2>";
    if ($inStockProductsResult->num_rows > 0) {
        echo "<table><tr><th>Reel Number</th><th>Width</th><th>Breaks</th><th>Location</th><th>Status</th></tr>";
        while ($row = $inStockProductsResult->fetch_assoc()) {
            echo "<tr><td>" . $row["ReelNumber"] . "</td><td>" . $row["Width"] . "</td><td>" . $row["Breaks"] . "</td><td>" . $row["Location"] . "</td><td>" . $row["Status"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No In-Stock Reels Available<br>";
    }

    if ($recentSalesResult->num_rows > 0) {
    echo "<table><tr><th>Sale ID</th><th>Customer ID</th><th>Sale Amount</th><th>Date</th></tr>";
    while ($row = $recentSalesResult->fetch_assoc()) {
        $jalaliDate = jdate("Y/m/d", strtotime($row["Date"])); // Convert to Jalali
        echo "<tr><td>" . $row["SaleID"] . "</td><td>" . $row["CustomerID"] . "</td><td>" . $row["SaleAmount"] . "</td><td>" . $jalaliDate . "</td></tr>"; // Use Jalali Date
    }
    echo "</table>";
    } else {
        echo "No Recent Sales Orders<br>";
    }

    // Section 5: Recent Purchases
    echo "<h2>Recent Purchases</h2>";
    if ($recentPurchasesResult->num_rows > 0) {
        echo "<table><tr><th>Purchase ID</th><th>Supplier ID</th><th>Cost</th><th>Purchase Date</th></tr>";
        while ($row = $recentPurchasesResult->fetch_assoc()) {
            echo "<tr><td>" . $row["PurchaseID"] . "</td><td>" . $row["SupplierID"] . "</td><td>" . $row["PricePerKG"] . "</td><td>" . $row["Date"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No Recent Purchases<br>";
    }

    // Section 6: Alerts and Notices
    echo "<h2>Alerts and Notices</h2>";
    echo $alerts;

echo "</body>
</html>";

$conn->close(); // Close the database connection
?>
