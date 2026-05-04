<?php

$conn = mysqli_connect("localhost","root","shree","student_db");

if(!$conn){
    die("Connection Failed");
}

/* DELETE */

if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM students WHERE id=$id");
    header("Location: index.php");
    exit();
}

/* FETCH RECORD FOR EDIT */

$editMode=false;
$name="";
$email="";
$id="";

if(isset($_GET['edit'])){
    $id=$_GET['edit'];
    $result=mysqli_query($conn,"SELECT * FROM students WHERE id=$id");
    $row=mysqli_fetch_assoc($result);

    $name=$row['name'];
    $email=$row['email'];
    $editMode=true;
}

/* INSERT */

if(isset($_POST['insert'])){
    $name=$_POST['name'];
    $email=$_POST['email'];

    mysqli_query($conn,"INSERT INTO students(name,email) VALUES('$name','$email')");
    header("Location: index.php");
    exit();
}

/* UPDATE */

if(isset($_POST['update'])){
    $id=$_POST['id'];
    $name=$_POST['name'];
    $email=$_POST['email'];

    mysqli_query($conn,"UPDATE students SET name='$name', email='$email' WHERE id=$id");
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Student Management</title>

<style>

body{
font-family: Arial;
margin:40px;
background:#f4f4f4;
}

.container{
width:600px;
margin:auto;
background:white;
padding:20px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
}

input{
padding:8px;
margin:5px;
width:90%;
}

button{
padding:8px 15px;
background:#007BFF;
color:white;
border:none;
border-radius:4px;
cursor:pointer;
}

button:hover{
background:#0056b3;
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

table,th,td{
border:1px solid #ccc;
padding:8px;
text-align:center;
}

a{
text-decoration:none;
color:red;
}

.edit{
color:green;
}

</style>

</head>

<body>

<div class="container">

<h2>Student Management System</h2>

<form method="post">

<input type="hidden" name="id" value="<?php echo $id; ?>">

<input type="text" name="name" placeholder="Enter Name" value="<?php echo $name; ?>" required>

<input type="email" name="email" placeholder="Enter Email" value="<?php echo $email; ?>" required>

<br>

<?php if($editMode){ ?>

<button type="submit" name="update">Update Student</button>

<?php } else { ?>

<button type="submit" name="insert">Add Student</button>

<?php } ?>

</form>

<h3>Student Records</h3>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>

<?php

$result=mysqli_query($conn,"SELECT * FROM students");

while($row=mysqli_fetch_assoc($result)){
?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>

<td>
<a class="edit" href="?edit=<?php echo $row['id']; ?>">Edit</a> |
<a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this record?')">Delete</a>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>