<?php
session_start();
include 'templates/nav2.php';
include 'templates/base2.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>

        .make-it-center{
            margin: auto;
            width: 50%;
        }

        body{
            color: white;

        }

        .lefterr{
            margin-left: -10%;
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
$errCount = 0;

$purl = "";
$acc_username ="";
$pUrlErr = "";

if (isset($_SESSION['uname']) && isset($_SESSION['ugroup'])) {
    if ($_SESSION['ugroup'] === 'Subscriber') {
        echo '<br><div style="text-align: center">' . $_SESSION['ugroup'] . ' is not allowed to perform this task!</div>';
        header('HTTP/1.0 405 Not Allowed', true, 405);
        exit();
    }
}

// array code
$strJsonFileContents = file_get_contents("data.json");
// var_dump($strJsonFileContents);
$arra = json_decode($strJsonFileContents);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["purl"])) {
        $pUrlErr = "URL is required!";
        $errCount = $errCount + 1;
    } else {
        $purl = $_POST["purl"];
    }

    if (!empty($_POST["acc_username"])) {
        $acc_username = check_input($_POST["acc_username"]);
    }else{
        $errCount = $errCount +1;
    }


    if ($errCount < 1){

        $strJsonFileContents = file_get_contents("data.json");
        $arra = json_decode($strJsonFileContents);
        $user_found = false;
        $user_edited = false;
        foreach($arra as $item) { //foreach element in $arr
            if ($acc_username === $item->username){
                $user_found = true;
                if (isset($item->trackers)){
                    array_push($item->trackers, $purl);
                    $user_edited = true;
                } else{
                    $item->trackers = array($purl);
                    $user_edited = true;
                }
            }
        }
        if ($user_edited){
            $final_data = json_encode($arra);
            if(file_put_contents('data.json', $final_data)){
                echo "<br><div style='color: green; text-align: center'> Successfully submitted! </br></div>";
                echo "<div style='color: green; text-align: center'> Whenever there's a change of stock status (eg In-Stock/Stock-Out) You will get notified.</br></div>";

            }
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

<div class="donor-info make-it-center">
    <h2>Add a stock tracker for an Account</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="acc_username">Select an account:</label>
        <select name="acc_username" id="acc_username">
            <?php

            foreach ($arra as $item) { //foreach element in $arr
                if ($item->acc_group !== 'Admin' && $item->acc_group !== 'Support') {
                    echo "<option>$item->username</option>";
                }

            }

            ?>
        </select> <br><br>
        Enter Product URL: <input type="text" name="purl" value="<?php echo $purl;?>">
        <span class="error">* <?php echo $pUrlErr;?></span>
        <br><br>

        <input type="submit" name="submit" value="Submit">

    </form>

</div>


</body>

</html>