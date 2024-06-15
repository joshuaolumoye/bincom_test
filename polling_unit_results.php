<?php
include "config.php";

if (isset($_GET['polling_unit_id'])) {
    $polling_unit_id = intval($_GET['polling_unit_id']);
    
    $sql = "SELECT party_abbreviation, party_score FROM announced_pu_results WHERE polling_unit_uniqueid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $polling_unit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<h1>Polling Unit Results</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Party</th><th>Score</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['party_abbreviation'] . "</td><td>" . $row['party_score'] . "</td></tr>";
    }
    echo "</table>";
    
    $stmt->close();
} else {
    echo "No polling unit ID provided.";
}

$conn->close();
?>
