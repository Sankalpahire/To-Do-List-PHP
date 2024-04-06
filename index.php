<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
   
    $sql = "DELETE FROM tasks WHERE id = '$delete_id'";
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Task deleted successfully")</script>';
    } else {
        echo '<script>alert("Error deleting task: ' . $conn->error . '")</script>';
    }
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // if (isset($_POST['update_id'])) {
    //     // Update operation
    //     $update_id = $_POST['update_id'];
    //     $name = $_POST['name'];
    //     $task = $_POST['task'];

    //     $sql = "UPDATE tasks SET name='$name', task='$task' WHERE id='$update_id'";
    //     if ($conn->query($sql) === TRUE) {
    //         echo '<script>alert("Task updated successfully")</script>';
    //     } else {
    //         echo '<script>alert("Error updating task: ' . $conn->error . '")</script>';
    //     }
    // }
     if (isset($_POST['name']) && isset($_POST['task'])) {
        // Insert operation
        $name = $_POST['name'];
        $task = $_POST['task'];

        if (!empty($name) && !empty($task)) {
            $sql = "INSERT INTO tasks (name, task) VALUES ('$name', '$task')";

            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("New record created successfully")</script>';
            } else {
                echo '<script>alert("Error: ' . $sql . '<br>' . $conn->error . '")</script>';
            }
        } else {
            echo '<script>alert("Name and task cannot be empty")</script>';
        }
    }
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>To-Do-List with PHP!</title>
</head>

<body>
    <!-- add task modal -->
    <div class="container my-5">
        <h1>To-Do-List</h1>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#openModal">Add Task</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="openModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Task Here</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                            <div class="form-text">we will use this name to identify your task</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Task</label>
                            <input type="text" class="form-control" name="task" required>
                            <div class="form-text">we will use this name to identify your task</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- update modal -->

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" action="update.php" method="POST">
                        <!-- Update form fields -->
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" id="updateName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Task</label>
                            <input type="text" class="form-control" id="updateTask" name="task" required>
                        </div>
                        <!-- Add hidden input for task ID -->
                        <input type="hidden" id="update_Id" name="update_id">
                        <!-- Change the button type to button -->
                        <button type="submit" class="btn btn-primary" >Update</button>
                        <!-- onclick="submitUpdateForm()" <- this function used with ajax -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container border border-1">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Task</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connection.php';

                $sql = "SELECT * FROM tasks";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $task = $row['task'];
                        $timestamp = $row['timestamp'];
                        echo "<tr>";
                        echo "<th scope='row'>$id</th>";
                        echo "<td>$name</td>";
                        echo "<td>$task</td>";
                        echo "<td>$timestamp</td>";
                        echo "<td>";
                        echo "<button type='button' class='btn btn-warning me-3' data-bs-toggle='modal' data-bs-target='#updateModal' onclick='populateUpdateModal($id, \"$name\", \"$task\")'>Update</button>";
                        echo "<form method='POST' style='display: inline;'>";
                        echo "<input type='hidden' name='delete_id' value='$id'>";
                        echo "<button type='submit' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this task?\")'>Delete</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>0 results</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function populateUpdateModal(id, name, task) {
            document.getElementById('update_Id').value = id;
            document.getElementById('updateName').value = name;
            document.getElementById('updateTask').value = task;
        }

        // function submitUpdateForm() {
            
        //     var formData = $('#updateForm').serialize();

           
        //     $.ajax({
        //         type: 'POST',
        //         url: 'index.php',
        //         data: formData,
        //         success: function(response) {
        //             // Handle success response
        //             alert('Task updated successfully');
                    
        //             $('#updateModal').modal('hide');
        //             location.reload(); 
        //         },
        //         error: function(xhr, status, error) {
        //             // Handle error response
        //             alert('Error updating task: ' + error);
        //         }
        //     });
        // }
    </script>
</body>

</html>
