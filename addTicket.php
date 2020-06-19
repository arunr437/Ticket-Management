<?php

session_start();

if(!isset($_SESSION['userid']))
{
    header('location: Index.php');
}
$validationFlag=-1;
$subjectError = $messageError = "";

//Code to perform form validation and add the new ticket into ticket.xml
if(isset($_POST['submit']))
{
    $inUserId = $_SESSION['userid'];
    $inSubject = $_POST['subject'];
    $inMessage = $_POST['message'];
    $inCategory = $_POST['category'];
    $inPriority = $_POST['priority'];
    $inTicketId = 0;
    $validationFlag = 0;

    if($inSubject=="")
    {
        $validationFlag = 1;
        $subjectError = "Please enter a subject";
    }

    if($inMessage=="")
    {
        $validationFlag = 1;
        $messageError = "Please enter a message";
    }

    if($validationFlag==0) {

        $ticketDoc = new DOMDocument('1.0', "utf-8");
        $ticketDoc->load("XML/tickets.xml");
        $ticketDoc->preserveWhiteSpace = false;
        $ticketDoc->formatOutput = true;
        $tickets = $ticketDoc->getElementsByTagName("ticket");

        foreach ($tickets as $ticket) {
            $currentId = $ticket->getElementsByTagName('id')[0]->nodeValue;
            if ($currentId > $inTicketId) {
                $inTicketId = $currentId;
            }
        }

        $tickets = $ticketDoc->getElementsByTagName('tickets')[0];
        $ticket = $ticketDoc->createElement("ticket");
        $id = $ticketDoc->createElement("id", $inTicketId + 1);
        $id->setAttribute("priority", $inPriority);
        $date = $ticketDoc->createElement("date");
        $dd = $ticketDoc->createElement("dd", date("d"));
        $mm = $ticketDoc->createElement("mm", date("m"));
        $yyyy = $ticketDoc->createElement("yyyy", date("Y"));
        $date->appendChild($dd);
        $date->appendChild($mm);
        $date->appendChild($yyyy);
        $status = $ticketDoc->createElement("status", "Pending");
        $subject = $ticketDoc->createElement("subject", $inSubject);
        $subject->setAttribute("type", $inCategory);
        $messages = $ticketDoc->createElement("messages");
        $message = $ticketDoc->createElement("message", $inMessage);
        $message->setAttribute("user","Client");
        $message->setAttribute("date",date('c'));
        $messages->appendChild($message);
        $userId = $ticketDoc->createElement("userid", $inUserId);
        $ticket->appendChild($id);
        $ticket->appendChild($date);
        $ticket->appendChild($status);
        $ticket->appendChild($subject);
        $ticket->appendChild($messages);
        $ticket->appendChild($userId);
        $tickets->appendChild($ticket);
        $ticketDoc->save("XML/tickets.xml");
        header('location: TicketList.php?userid=' . $inUserId);
    }
}

?>

<html>
    <head>
        <title>Ticket Details</title>
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
                        <li><span class="link" ><a href="addTicket.php">New Ticket</a></li>
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
            <section id="addTicketForm">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                            <div class="card my-5">
                                <div class="card-body">
                                    <h2 class="card-title text-center">Add Ticket</h2>
                                    <form method="post">
                                        <div class="form-label-group">
                                            <label for="subject">Subject</label>
                                            <input type="text"  class="form-control" id="subject" name="subject" placeholder="Enter ticket subject"/>
                                            <span id="errorMessage"><?=$subjectError ?></span>
                                        </div>

                                        <div class="form-label-group">
                                            <label  for="message">Message</label>
                                            <input type="text" class="form-control" name="message" placeholder="Enter details"/>
                                            <span id="errorMessage"><?=$messageError?></span>
                                        </div>
                                        <div class="form-label-group">
                                            <label  for="type">Issue Category:</label>
                                            <select class="form-control" id="category" name="category">
                                                <option value="OS">OS</option>
                                                <option value="Application">Application</option>
                                                <option value="Hardware">Hardware</option>
                                                <option value="Network">Network</option>
                                            </select>
                                        </div>
                                        <div class="form-label-group">
                                            <label for="priority">Priority</label>
                                            <select class="form-control" id="priority" name="priority">
                                                <option value="Low">Low</option>
                                                <option value="Medium">Medium</option>
                                                <option value="High">High</option>
                                            </select>
                                        </div>
                                        <input type="submit" name="submit" id="button" class="btn btn-primary"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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