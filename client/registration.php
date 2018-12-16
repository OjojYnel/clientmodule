<?php
include 'config.php';



// initializing variables
$username = "";
$firstname = "";
$lastname = "";
$errors = array(); 



// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($con, $_POST['username']);
  $firstname = mysqli_real_escape_string($con, $_POST['first_name']);
  $lastname = mysqli_real_escape_string($con, $_POST['last_name']);
  $password_1 = mysqli_real_escape_string($con, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($con, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { 
      array_push($errors, "Username is required"); 
}
  if (empty($firstname)) { 
      array_push($errors, "First Name is required"); 
}
  if (empty($lastname)) { 
      array_push($errors, "Last Name is required"); 
    }
  if (empty($password_1)) { 
      array_push($errors, "Password is required"); 
    }
  if ($password_1 != $password_2) {
    array_push($errors, "The two passwords do not match");
  }
   

  // first check the database to make sure 
  // a user does not already exist with the same username 
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR first_name='$firstname' LIMIT 1";
  $result = mysqli_query($con, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) { 
      array_push($errors, "Username already exists");
    }

  }

  if ($user) { // if firstname exists
    if ($user['first_name'] === $firstname) {
    }
      array_push($errors, "First name already exists");
    
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, first_name, last_name, password) 
  			  VALUES('$username','$firstname','$lastname','$password')";
  	mysqli_query($con, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}