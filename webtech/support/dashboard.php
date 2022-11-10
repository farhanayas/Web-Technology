<!--
{% extends 'base.html' %}
{% load custom_tags %}

{%  block authnav %}
    {% if auth %}
        <a href="/profile/">Profile</a>
        <a href="/login/">Logout</a>
        {% else %}
        <a href="/login/">Login</a>
    {% endif %}
{% endblock %}

-->


<?php
session_start();
include 'templates/nav2.php';
include 'templates/base2.php';
// define variables and set to empty values
$userErr = $passErr = "";
$username = $password = "";
$errCount = 0;

if (isset($_SESSION['uname'])) {

}else{
    header('Location: login.php');
}

?>

<br>
<h1 style="text-align: center; color: white"> Welcome to Cartista Support Panel</h1>

