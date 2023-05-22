<?php

$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'form';

$connect = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

//to check if error connecting to datbase
if(mysqli_connect_error()) {
    exit('Error connecting to the database ' . mysqli_connect_error());
}

//to check if any field are empty
if(!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    exit('Empty Field(s)');
}

if(empty($_POST['username'] || empty($_POST['password']) || empty($_POST['email']))) {
    exit('Values Empty');
}

//to check if username is already taken
if($statment = $connect->prepare('SELECT id, password FROM users WHERE username = ?')) {
    $statment->bind_param('s', $_POST['username']);
    $statment->execute();
    $statment->store_result();

    if($statment->num_rows>0) {
        echo 'Username already exists!';
    }
    else {
        if($statment = $connect->prepare('INSERT INTO users (username, password, email) VALUE (?,?,?)')) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $statment->bind_param('sss', $_POST['username'], $password, $_POST['email']);
            $statment->execute();
            echo 'Successfully Registered';
        }
    else {
        echo 'Error Occurred';
    }
}
$statment->close();
}
else {
    echo 'Error Occurred';
}
$connect->close();