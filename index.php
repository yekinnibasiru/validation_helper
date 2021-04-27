<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo |ligthweight validation helper class in php</title>
</head>
<body>
<?php
require 'Validation/Validation.php';
require 'Database/Db.php';

$Db=new Db();
//Set database credentials
$Db->dbhost='localhost';
$Db->dbusername='root';
$Db->dbpassword='';
$Db->dbname='testing';

//Get database connection string
$conn=$Db->getConnection();

$validation=new Validation($conn);
if(isset($_POST['submit'])){

    //You can call $validation->validate() as many times as possible
    $validation->validate('password','Password','required',array(
        'required' => "Enter your password"
    ));

    $validation->validate('passconf','Password Confirmation','required|matches[password]',array(
        'matches'=> "Password must match"
    ));
    //Check if there is error print_r array of validation errors,otherwise it echo form processed
    if($validation->run()){
        echo "Data processed sucessfully";
    }
    else{
        print_r($validation->validationErrors());
    }
}

?>

<form action="index.php" method="post">
    <input type="password" name="password">
    <p><?php echo $validation->formError('password'); ?></p>
    <input type="password" name="passconf">
    <p><?php echo $validation->formError('passconf'); ?></p>
    <input type="submit" value="submit" name="submit">
</form>
</body>
</html>

