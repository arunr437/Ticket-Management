<?php
session_start();

if(!isset($_SESSION['username']))
{
    header('location: Index.php');
}

$_SESSION = [];
?>
<html>
    <head>
        <title>Logout</title>
    </head>
    <body>
        <nav>
            <a href="Index.php">Login</a> |
            <a href="TicketList.php">TicketList</a> |
            <a href="Logout.php">Logout</a> |
        </nav>
        <h1>Logged Out</h1>
    </body>
</html>