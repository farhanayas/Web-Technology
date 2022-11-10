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
}

$strJsonFileContents = file_get_contents("data.json");
// var_dump($strJsonFileContents);

$arra = json_decode($strJsonFileContents);
echo '
    <h2>Subscriber Accounts List</h2>
<h3 style="text-align: center"><a href="./edit_any_account.php">Edit an Account</a></h3> 
    <table>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Password</th>
            <th>Email</th>
            <th>Group</th>
            <th>Status</th>
        </tr>
    ';
foreach($arra as $item) { //foreach element in $arr
    if ($item->acc_group==='Subscriber') {
        echo "
        <tr>
            <td>$item->name</td>
            <td>$item->username</td>
            <td>$item->password</td>
            <td>$item->email</td>
            <td>$item->acc_group</td>
            <td>$item->status</td>
        </tr>
        ";
    }
}
echo '
    </table>
';
?>
<!--
<br><br>
<h3 style="text-align: center"><a href="/cartista/create_new_user.php"> Add New Account </a></h3>
-->