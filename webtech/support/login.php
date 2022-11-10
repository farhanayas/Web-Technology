<?php
session_start();
include 'templates/nav2.php';?>
<?php include 'templates/base2.php';?>
<?php
// define variables and set to empty values
$userErr = $passErr = "";
$username = $password = "";
$errCount = 0;

if (isset($_SESSION['uname'])) {
    header('Location: dashboard.php');

} else{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["username"])) {
            $userErr = "Username is required";
            $errCount = $errCount + 1;
        } else {
            $username = check_input($_POST["username"]);

            if (strlen($username) <2 ) {
                // code...
                $userErr = "Minimum 2 characters required";
                $errCount = $errCount + 1;
            }elseif (!preg_match("/^[a-zA-Z_\-.]*$/", $username)) {
                $userErr = "Username can contain alpha numeric characters, period, dash or underscore only!";
                $username ="";
                $errCount = $errCount + 1;
            } else{
                if (isset($_POST['rmbm'])) {
                    $time = time();
                    setcookie('username', $username, $time + 120);
                    setcookie('password', $password, $time + 120);
                }
            }

        }

        if (empty($_POST["password"])) {
            $passErr = "Password is required";
            $errCount = $errCount + 1;
        } else {
            $password = check_input($_POST["password"]);
        }

        if (strlen($password) <8 ) {
            // code...
            $passErr = "Minimum 8 characters required";
            $errCount = $errCount + 1;
        }

        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?!.* )(?=.*[%$#@]).+$/", $password)) {
            /*
            ^ starts the string
            (?=.*[a-z]) Atleast a lower case letter
            (?=.*[A-Z]) Atleast an upper case letter
            (?!.* ) no space
            (?=.*\d%$#@) Atleast a digit and atleast one of the specified characters.
            .{8,16} between 8 to 16 characters
            */
            $passErr .= " Password must contain atleast a digit, a lower case and an upper case letter, atleast one of the [%$#@] and no space.";
            $password ="";
            $errCount = $errCount + 1;
        }

        if ($errCount < 1){
            $time = time();
            if (isset($_POST['rmbm'])) {
                setcookie('username', $username, $time + 10);
                setcookie('password', $password, $time + 10);
            }

            $strJsonFileContents = file_get_contents("data.json");
            // var_dump($strJsonFileContents);

            $arra = json_decode($strJsonFileContents);
            // var_dump($arra);
            $user_found = false;
            foreach($arra as $item) { //foreach element in $arr
                if ($username === $item->username){
                    $user_found = true;
                    // match. now check pw
                    if ($password === $item->password){
                        echo "Thanks for logging Mr. $item->name ... success!!";
                        $_SESSION['uname'] = $username;
                        $_SESSION['ugroup'] = $item->acc_group;
                        header('Location: dashboard.php');
                        //exit;
                    }else{
                        $passErr .= "Password Wrong!";
                    }
                }
            }
            if (!$user_found){
                echo $userErr .= "No account found!";
            }


            //exit;

        }

    }
}

function check_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<br><br>
<h4 style="color: white; text-align: center">You are not logged in! Please Login!</h4>

<div class="login-page">
          <div class="form">
            <form method="POST" class="login-form">
                <div style="text-align: left"> </div>
                Username:
                <span class="error">* <?php echo $userErr;?></span>
                <input type="text" name="username" value="<?php if(isset($_COOKIE["username"])) { echo $_COOKIE["username"]; } ?>">

                Password:
                <span class="error">* <?php echo $passErr;?></span>
                <input type="password" name="password" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>">
                <br>

                <input type="checkbox" id="rmbm" name="rmbm" value="True">
                <label for="rmbm"> Remember Me</label><br><br>

                <button type="submit" value="Log in">login</button> <br> <br>

                <a href="/webtech/Lab4/forgot.php"> <span>Forgot Password?</span> </a>

            </form>
          </div>

</div>
