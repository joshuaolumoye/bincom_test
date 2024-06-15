<?php
include "config.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $polling_unit_id = intval($_POST['polling_unit_id']);
    $party_results = $_POST['party_results'];
    
    foreach ($party_results as $party => $score) {
        $party = mysqli_real_escape_string($conn, $party);
        $score = intval($score);
        
        $sql = "INSERT INTO announced_pu_results (polling_unit_uniqueid, party_abbreviation, party_score) VALUES ('$polling_unit_id', '$party', '$score')";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
    echo "<div class='success-message'>Results added successfully.</div>";
}

// Fetch polling unit IDs from the database
$sql = "SELECT uniqueid, polling_unit_name FROM polling_unit";
$result = $conn->query($sql);
$polling_units = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $polling_units[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Polling Unit Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 40%;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"], select {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Polling Unit Results</h1>
        <form method="POST" action="">
            <label for="polling_unit_id">Polling Unit:</label>
            <select name="polling_unit_id" required>
                <option value="">Select Polling Unit</option>
                <?php foreach ($polling_units as $unit) : ?>
                    <option value="<?php echo $unit['uniqueid']; ?>"><?php echo $unit['polling_unit_name']; ?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="party_results">Party Results:</label><br>
            <input type="text" name="party_results[PDP]" placeholder="PDP Score"><br>
            <input type="text" name="party_results[DPP]" placeholder="DPP Score"><br>
            <input type="text" name="party_results[ACN]" placeholder="ACN Score"><br>
            <input type="text" name="party_results[PPA]" placeholder="PPA Score"><br>
            <input type="text" name="party_results[CDC]" placeholder="CDC Score"><br>
            <input type="text" name="party_results[JP]" placeholder="JP Score"><br>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
