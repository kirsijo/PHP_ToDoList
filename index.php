<?php 

//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);

// Connect to DB
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  echo ""; 
  //"Welcome to your todo app!";
}


//Inserting values to database
if (isset($_POST['submit'])) {
    $task = $_POST['task'];
    if ($task) {
        mysqli_query($conn,"INSERT INTO tasks (task) VALUES ('$task')");
    } else {
        $error = "Please fill in a task";
    }    
}

// Deleting tasks 
if (isset($_POST['deletetask'])) {
    $id = $_POST['deletetask']; 
    mysqli_query($conn, "DELETE FROM tasks WHERE id='$id'");
}

// Updating tasks to done 
if (isset($_POST['updatedtask'])) {
    $id = $_POST['updatedtask'];
    mysqli_query($conn, "UPDATE tasks SET done=true WHERE id='$id'");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>To Do App - Team Front Row</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
</head>

<body>
    <h1>To Do list</h1>
  <form action="index.php" method="post">
  <?php if (isset($error)) { ?>
	<p><?php echo $error; ?></p>
<?php } ?>
<div id="add-task">
    <label for="task"> </label>
    <input type="text" name="task" required>
    <input type="submit" id="submit" name="submit" value="Add task">
  </div>
  </form>
  <table>
      <tr>
          <th>Tasks</th>
          <th>Delete</th>
          <th>Done</th>
      </tr>
      <tbody>
 <?php $tasklist = mysqli_query($conn, "SELECT * FROM tasks");
while ($row = mysqli_fetch_assoc($tasklist)) {
    //Setting conditional classes to tasks that are done/to-do
    if ($row['done']) { ?>
     <tr class="item-done">
        <?php } else { ?>
            <tr id="list" class="item-todo">
       <?php } ?>
     
<td class="task"> 
<?php echo $row['task']; ?>
    </td>
<td class="delete">
    <form action="index.php" method="post">
        <button type="submit" name="deletetask" value="<?php echo $row['id']?>" >
        <span class="material-icons">delete</span>
        </button>
    </form>
</td> 

<td class="checked">
<form action="index.php" method="post">
        <button type="submit" name="updatedtask" value="<?php echo $row['id']?>" >
        <span class="material-icons">done</span>
        </button>
</form>

</td>
</tr>
        <?php } ?>
        </tbody>
  </table>
</body>
</html>