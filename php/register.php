<?php
    function validatePassword($password) {
        if (strlen($password)<8)
            return "Password must be at least 8 charactes long";
        
        if(!preg_match("/[A-Z]/", $password)) {
        return "Password must contain at least one uppercase letter.";
        }

        if(!preg_match("/[0-9]/", $password)) {
        return "Password must contain at least one number.";
        }

        if(!preg_match("/[\W_]/", $password)) {
        return "Password must contain at least one special character.";
        }
    return true;
    }

    if (isset($_POST["submit"])) {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["repeatPassword"];
    
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $role = $_POST["role"];

        if($password !== $repeat_password) {
            echo "Passwords do not match.";
        }
        else {
            $passwordRepeat = validatePassword($password);

            //insert data into database
        }
    }



?>