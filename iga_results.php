<?php
include "config.php";

if (isset($_POST['lga_id'])) {
    $lga_id = intval($_POST['lga_id']);
    echo '
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        select, input[type="submit"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
    </style>';
    
    $sql = "
        SELECT party_abbreviation, SUM(party_score) AS total_score
        FROM announced_pu_results
        JOIN polling_unit ON announced_pu_results.polling_unit_uniqueid = polling_unit.uniqueid
        WHERE polling_unit.lga_id = ?
        GROUP BY party_abbreviation
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lga_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo '<div class="container">';
    echo "<h1>LGA Results</h1>";
    echo "<table>";
    echo "<tr><th>Party</th><th>Total Score</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . htmlspecialchars($row['party_abbreviation']) . "</td><td>" . htmlspecialchars($row['total_score']) . "</td></tr>";
    }
    echo "</table>";
    echo '</div>';
    
    $stmt->close();
} else {
    echo '<div class="container">';
    echo "<form method='POST' action=''>";
    echo "<label for='lga_id'>Select LGA:</label>";
    echo "<select name='lga_id'>";
    
    $sql = "SELECT lga_id, lga_name FROM lga WHERE state_id = 25";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($row['lga_id']) . "'>" . htmlspecialchars($row['lga_name']) . "</option>";
    }
    
    echo "</select>";
    echo "<input type='submit' value='Show Results'>";
    echo "</form>";
    echo '</div>';
}

$conn->close();
?>
