<?php
date_default_timezone_set('US/Eastern');
session_start();

if(!isset($_SESSION['userid']))
{
    header('location: Index.php');
}

$adminName = "";
$ticketId = $_GET['ticketid'];
$userType = $_GET['usertype'];

//Below code adds the new messages entered by admin/client in the chat box to  tickets.xml
if(isset($_POST['submit']))
{
    $ticketOut = new DOMDocument('1.0', "utf-8");
    $ticketOut->load("XML/tickets.xml");
    $tickets = $ticketOut->getElementsByTagName("ticket");
    foreach ($tickets as $ticket)
    {
        if($ticket->getElementsByTagName("id")[0]->nodeValue==$ticketId)
        {
            $messages = $ticket->getElementsByTagName('messages')[0];
            $message = $ticketOut->createElement("message" , $_POST['message']);
            if($userType=="Client")
            {
                $message->setAttribute("user", "Client");
                $message->setAttribute("date",date('c'));
            }
            else
            {
                $message->setAttribute("user", "Admin");
                $message->setAttribute("date",date('c'));
            }
            $messages->appendChild($message);
            $ticketOut->formatOutput = true;
            $ticketOut->save("XML/tickets.xml");
        }
    }
}

//Below code is used to update the status of the ticket when the admin changes it
if(isset($_POST['change']))
{
    $ticketOut = new DOMDocument('1.0', "utf-8");
    $ticketOut->load("XML/tickets.xml");
    $tickets = $ticketOut->getElementsByTagName("ticket");
    foreach ($tickets as $ticket)
    {
        if($ticket->getElementsByTagName("id")[0]->nodeValue==$ticketId)
        {
            $ticket->getElementsByTagName('status')[0]->nodeValue = $_POST['status'];
            $ticketOut->formatOutput = true;
            $ticketOut->save("XML/tickets.xml");
        }
    }
    header('location: TicketList.php?userid='.$_SESSION['userid']);
}

$messages = new DOMNodeList();


//Code to get the details of the specfic ticket from Tickets.xml
$ticketsDoc = new DOMDocument('1.0', "utf-8");
$ticketsDoc->load("XML/tickets.xml");
$tickets = $ticketsDoc->getElementsByTagName("ticket");
foreach ($tickets as $ticket)
{
    if($ticketId ==  $ticket->getElementsByTagName('id')[0]->nodeValue)
    {
        $subject = $ticket->getElementsByTagName('subject')[0]->nodeValue;
        $status = $ticket->getElementsByTagName('status')[0]->nodeValue;
        $userId = $ticket->getElementsByTagName('userid')[0]->nodeValue;
        $messages = $ticket->getElementsByTagName('message');
    }
}

//Code to get details of the user
$userDoc = new DOMDocument('1.0', "utf-8");
$userDoc->load("XML/users.xml");
$users = $userDoc->getElementsByTagName("user");
foreach($users as $user)
{
    if($_SESSION['userid']== $user->getElementsByTagName('id')[0]->nodeValue)
    {
        $adminName = $user->getElementsByTagName('firstname')[0]->nodeValue;
    }
    if($userId==$user->getElementsByTagName('id')[0]->nodeValue)
    {
        $clientName = $user->getElementsByTagName('firstname')[0]->nodeValue;
    }
}
?>
<html>
    <head>
        <title>Ticket Details</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="Styles/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <main id="ticketDetails">
            <section class="ticketDetails">
                <div id="messageBox">
                <h3>Subject: <?=$subject ?></h3>
                    <div id="ChatBox">
                    <!-- Code to display the messages in the chatbox-->
                        <?php
                            foreach ($messages as $message)
                            {
                                $date =  $message->getAttribute("date");
                                $outDate = date("d-M h:i a",strtotime($date));
                               if($message->getAttribute('user')=='Admin')
                               {
                                   if($userType=="Client") {
                                       ?>
                                       <div class="messageLeft">
                                           <div class="messageStampLeft">
                                               <span>Admin:</span>
                                           </div>
                                           <div class="leftText">
                                               <span><?= $message->nodeValue ?></span>
                                           </div>
                                           <div class="date">
                                               <span><?=$outDate?></span>
                                           </div>
                                           <div class="clear"></div>
                                       </div>
                                       <?php
                                   }
                                   else {
                                       ?>
                                       <div class="messageRight">
                                           <div class="messageStampRight">
                                           </div>
                                           <div class="rightText">
                                               <span><?= $message->nodeValue ?></span>
                                           </div>
                                           <div class="date">
                                               <span><?=$outDate?></span>
                                           </div>
                                           <div class="clear"></div>
                                       </div>
                                       <?php
                                   }
                               }
                               else
                               {
                                   if ($userType=="Client") {
                                       ?>
                                       <div class="messageRight">
                                           <div class="messageStampRight">
                                           </div>
                                           <div class="rightText">
                                               <span><?= $message->nodeValue ?></span>
                                           </div>
                                           <div class="date">
                                               <span><?=$outDate?></span>
                                           </div>
                                           <div class="clear"></div>
                                       </div>
                                       <?php
                                   }
                                   else {
                                       ?>

                                       <div class="messageLeft">
                                           <div class="messageStampLeft">
                                               <span><?= $clientName ?>:</span>
                                           </div>
                                           <div class="leftText">
                                               <span><?= $message->nodeValue ?></span>
                                           </div>
                                           <div class="date">
                                               <span><?=$outDate?></span>
                                           </div>
                                           <div class="clear"></div>
                                       </div>
                                       <?php
                                   }
                               }
                        ?>

                        <?php
                            }
                        ?>
                    </div>
                    <div id="newMessage">
                        <form method="post" action="#">
                            <div id="writeMessage">
                                <input type="text" name="message" id="message" placeholder="Type a message">
                                <button type="submit" name="submit"  id="sendButton"><i class="fa fa-paper-plane-o"></i> </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <section id="changeStatus">
                <?php
                    if ($userType=="Admin") {
                        ?>
                        <section>
                            <form action="#" method="post">
                                <label>Change Status</label>
                                <select id="status" name="status">
                                    <option value="Pending" <?php if ($status=="Pending") echo "selected" ?>>Pending</option>
                                    <option value="Resolved" <?php if ($status=="Resolved") echo "selected" ?>>Resolved</option>
                                </select>
                                <input type="submit" name="change" class="btn btn-primary" value="Submit"/>
                            </form>
                        </section>
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

