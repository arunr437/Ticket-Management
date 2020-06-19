<?php
session_start();
date_default_timezone_set('US/Eastern');
$userName = "";


if(!isset($_SESSION['userid']))
{
    header('location: Index.php');
}


//Reading User.xml to display the current user details
$ticketsDoc = new DOMDocument('1.0', "utf-8");
$ticketsDoc->load("XML/tickets.xml");
$tickets = $ticketsDoc->getElementsByTagName("ticket");
$userType = "";
$userDoc = new DOMDocument();
$userDoc->load("XML/users.xml");
$users = $userDoc->getElementsByTagName("user");

foreach($users as $user)
{
    if($_SESSION['userid']== $user->getElementsByTagName('id')[0]->nodeValue)
    {
        $userName = $user->getElementsByTagName('firstname')[0]->nodeValue;
        $userType = $user->getAttribute('type');
    }
}


?>

<html>
    <head>
        <title>Ticket List</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="Styles/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="Scripts/Script.js"></script>
    </head>
    <body>
        <header>
            <nav id="navigation">
                <div id="logo">
                    <span><a href="#">Ticket Management System</a></span>
                </div>
                <div id="nav_links">
                    <ul>
                        <?php if($userType == "Client") echo "<li><span class=\"link\" ><a href=\"addTicket.php\">New Ticket</a></li>"?>
                        <li><span class="link" ><a href="TicketList.php">List Tickets</a></li>
                    </ul>
                </div>
                <div id="login">
                    <ul>
                        <li><span class="link"><a href="Logout.php">Logout</a></span></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <section id="ticketList">
                <?php
                if ($userType=="Admin") {
                    echo "<h1>Welcome, Admin</h1>";
                }
                else
                {
                ?>
                <h1>Welcome, <?=$userName ?></h1>
                <?php
                }
                ?>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Subject</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($tickets as $ticket)
                    {
                        if($userType=="Client" && $_SESSION['userid']!=$ticket->getElementsByTagName('userid')[0]->nodeValue) {
                            continue;
                        }
                    ?>
                        <tr>
                            <td><?=$ticket->getElementsByTagName('id')[0]->nodeValue ?></a></td>
                            <td><?=$ticket->getElementsByTagName('subject')[0]->nodeValue ?></td>
                            <td><?=$ticket->getElementsByTagName('id')[0]->getAttribute('priority') ?></td>
                            <td <?php if($ticket->getElementsByTagName('status')[0]->nodeValue == "Pending") {echo "class='yellow'";} else { echo "class='green'"; }?>><?=$ticket->getElementsByTagName('status')[0]->nodeValue ?></td>
                            <td><a href='TicketDetails.php?ticketid=<?=$ticket->getElementsByTagName('id')[0]->nodeValue ?>&usertype=<?=$userType?>' class="btn btn-primary <?php if($ticket->getElementsByTagName('status')[0]->nodeValue == "Resolved" && $userType=="Client") echo disabled ?>">View Ticket</a></td>

                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
                <?php
                if($userType=="Client") {
                ?>
                            <a href='addTicket.php' class='btn btn-primary'> New Ticket</a>
                <?php
                    }
                ?>
            </section>
        </main>
        <footer id="footer">
            <div id="footer_links">
                <ul>
                    <li><span class="link"><a href="#">Privacy</a></span></li>
                    <li>-</li>
                    <li><span class="link"><a href="#">About</a></span></li>
                    <li>-</li>
                    <li><span class="link"><a href="#">Contact US</a></span></li>
                </ul>
            </div>
            <div id="footer_copyright">
                <p>&copy; 2020 Arun Swaminathan Rathinam</p>
            </div>
        </footer>
    </body>
</html>
