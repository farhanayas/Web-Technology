<?php
session_start();
include 'templates/nav2.php';?>
<?php include 'templates/base2.php';?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Account</title>
    <style>
        body{
            color: white;

        }

        .required:after {
            content:"*";
            color: red;
        }
        .error{
            color: red;
        }


    </style>
</head>
<body>

<?php

// define variables and set to empty values
$userErr = $passErr = $accUsernameErr = $accGrpErr = $accStatusErr = "";
$username = $password = $acc_username = $acc_grp = $acc_status = "";
$errCount = 0;

if (isset($_SESSION['uname'])) {
    $name = $email = $gender = $dob = '';
    $err = '';

    echo "<h1>Edit A Subscriber Account </h1>";

    // array code
    $strJsonFileContents = file_get_contents("data.json");
    // var_dump($strJsonFileContents);
    $arra = json_decode($strJsonFileContents);

    // ends

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //$name = $email = $gender = $dob = '';
        $errCount = 0;
        if (!empty($_POST["name"])) {
            $name = check_input($_POST["name"]);
            $wcount = str_word_count($name);
            if ($wcount < 2 ) {
                // code...
                $nameErr = "Minimum 2 words required";
                $errCount = $errCount + 1;
            }

            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z]/", $name)) {
                $nameErr = "Name must start with a letter!";
                $name ="";
                $errCount = $errCount + 1;
            }

            if (!preg_match("/^[a-zA-Z_\-. ]*$/",$name)) {
                $nameErr = "Only letters, period and white space allowed";
                $name="";
                $errCount = $errCount + 1;
            }
        }

        if (!empty($_POST["email"])) {
            $email = check_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
                $email="";
                $errCount = $errCount + 1;
            }
        }

        if (!empty($_POST["acc_grp"])) {
            $acc_grp = check_input($_POST["acc_grp"]);
            if (!($acc_grp === 'Admin' || $acc_grp === 'Support' || $acc_grp === 'Subscriber')) {
                $accGrpErr = "Account type must be either Admin/Support/Subscriber";
                $errCount = $errCount + 1;
            }
        }

        if (!empty($_POST["acc_status"])) {
            $acc_status = check_input($_POST["acc_status"]);
        }

        if (!empty($_POST["gender"])) {
            $gender = check_input($_POST["gender"]);
        }

        if (!empty($_POST["acc_username"])) {
            $acc_username = check_input($_POST["acc_username"]);
        }else{
            $errCount = $errCount +1;
        }

        if (!empty($_POST["dob"])) {
            $dob = $_POST["dob"];
        }

        if($errCount > 0) {
            echo "<span class='error'>One or more error occurred!</span>";
        } else {
            if (file_exists('data.json')) {
                $strJsonFileContents = file_get_contents("data.json");
                $arra = json_decode($strJsonFileContents);
                // var_dump($arra);
                $user_found = false;
                $user_edited = false;
                foreach ($arra as $item) { //foreach element in $arr
                    if ($acc_username === $item->username) {
                        $user_found = true;
                        if ($name !== '') {
                            if (!($name === $item->name)) {
                                $item->name = $name;
                                $user_edited = true;
                            }
                        }
                        if ($email !== '') {
                            if (!($email === $item->email)) {
                                $item->email = $email;
                                $user_edited = true;
                            }
                        }
                        if ($gender !== '') {
                            if (!($gender === $item->gender)) {
                                $item->gender = $gender;
                                $user_edited = true;
                            }
                        }
                        if ($acc_grp !== '') {
                            if ($item->acc_group) {
                                if (!($acc_grp === $item->acc_group)) {
                                    $item->acc_group = $acc_grp;
                                    $user_edited = true;
                                }
                            }
                        }
                        if ($acc_status !== '') {
                            if ($item->status) {
                                if (!($acc_status === $item->status)) {
                                    $item->status = $acc_status;
                                    $user_edited = true;
                                }
                            }else{
                                $item->status = $acc_status;
                                $user_edited = true;
                            }
                        }

                        if ($dob !== '') {
                            if (!($dob === $item->dob)) {
                                $item->dob = $dob;
                                $user_edited = true;
                            }
                        }
                    }
                }
                if ($user_edited){
                    $final_data = json_encode($arra);
                    if(file_put_contents('data.json', $final_data)){
                        echo "<span style='color: green'>User Edited Successfully!</span>";
                    }
                }
            }
        }


    } else {

        $strJsonFileContents = file_get_contents("data.json");
        // var_dump($strJsonFileContents);
        $arra = json_decode($strJsonFileContents);
        // var_dump($arra);

        foreach ($arra as $item) { //foreach element in $arr
            if ($_SESSION['uname'] === $item->username) {
                // do stuff
                $name = $item->name;
                $email = $item->email;
                $gender = $item->gender;
                $dob = $item->dob;
            }
        }
    }

} else{
    header('Location: login.php');
}
function check_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
<br>
<fieldset>
    <legend> <b>Profile:</b></legend>
    <div class="donor-info">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

            <label for="acc_username">Select an account:</label>
            <select name="acc_username" id="acc_username">
            <?php

            foreach ($arra as $item) { //foreach element in $arr
                if ($item->acc_group === 'Subscriber') {
                    echo "<option>$item->username</option>";
                }

            }

            ?>
            </select> <br><br>


            New Name: <input type="text" name="name" value="">
            <br><br>
            New Email: <input type="text" name="email" value="">
            <br><br>
            <span>Gender: </span>
            <input type="radio" id="male" name="gender" value="male" >
            <label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="female">
            <label for="female">Female</label>
            <input type="radio" id="other" name="gender" value="other">
            <label for="other">Other</label>
            <br><br>
            <span>Date of Birth: </span>
            <input type="date" name="dob"> <br><br>
            <br>

            <label for="acc_grp">Change Account Type:</label>
            <select name="acc_grp" id="acc_grp">
                <option value="">Select</option>
                <option value="Support">Support</option>
                <option value="Subscriber">Subscriber</option>
            </select> <br><br>

            <label for="acc_status">Change Account Status:</label>
            <select name="acc_status" id="acc_status">
                <option value="">Select</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select> <br><br>

            <input type="submit" name="submit" value="Submit">
        </form>

    </div>
</fieldset>
</body>
</html>