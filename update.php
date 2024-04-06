<?php 
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_id'])) {
        // Update operation
        $update_id = $_POST['update_id'];
        $name = $_POST['name'];
        $task = $_POST['task'];

        $sql = "UPDATE tasks SET name=?, task=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $task, $update_id);
        
        if ($stmt->execute() === TRUE) {
            $stmt->close(); 
            header("Location: index.php"); 
            exit; 
        } else {
            echo '<script>alert("Error updating task: ' . $stmt->error . '")</script>';
        }
    }
}
?>
