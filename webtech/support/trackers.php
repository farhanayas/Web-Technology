<style>
    body{
        color: white;
    }
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;

    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }



    tr:nth-child(even) {
        background-color: #1d3858;
    }
    a{
        color: white;
    }
</style>

<?php
session_start();
include 'templates/nav2.php';?>
<?php include 'templates/base2.php';

if (!isset($_SESSION['uname'])) {
    header('Location: login.php');
}else{
    if ($_SESSION['ugroup']==='Subscriber'){
        echo '<br><div style="text-align: center; color: white">' . $_SESSION['ugroup']. ' is not allowed to perform this task!</div>';
        header('HTTP/1.0 405 Not Allowed', true, 405);
        exit();
    }
}

$strJsonFileContents = file_get_contents("data.json");
// var_dump($strJsonFileContents);

$arra = json_decode($strJsonFileContents);
$is_ad = which_group($arra);

echo '
    <h2>Tracker URL List</h2> 
<h3 style="text-align: center"><a href="./create_new_tracker.php"> Create New Tracker </a></h3> 
    <table>
        <tr>
            <th>Username</th>
            <th>URL</th>
        </tr>
    ';
foreach($arra as $item) { //foreach element in $arr
    if ($_SESSION['ugroup'] !== 'Subscriber') {
        if (isset($item->trackers)) {
            foreach ($item->trackers as $eitem) {
                echo "
            <tr>
                <td> $item->username</td>
                <td>$eitem</td>
            </tr>
            ";
            }
        }

    }
}
echo '
    </table>
';

function which_group($arra) {
    foreach($arra as $item) {
        if ($_SESSION['uname']==$item->username){
            // match
            if ($item->acc_group === 'Admin'){
                return 'Admin';
            } elseif ($item->acc_group === 'Support'){
                return 'Support';
            } else {
                return 'Subscriber';
            }
        }
    }
    return false;
}
?>
<!--
<br><br>
<h3 style="text-align: center"><a href="/cartista/create_new_user.php"> Add New Account </a></h3>
-->