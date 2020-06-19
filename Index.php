<?php
session_start();
$errorMessage = "";
if(isset($_POST['submit']))
{
    //Setting the session variables for userid and password
    $_SESSION['userid'] = $_POST['userid'];
    $_SESSION['password'] = $_POST['password'];


    //Getting the list of users from the user.xml for validation.
    $doc = new DOMDocument('1.0', "utf-8");
    $doc->load("XML/users.xml");

    //Checking if the entered userid and password is present in users.xml
    $users = $doc->getElementsByTagName("user");
    foreach ($users as $user)
    {
        $userid = $user->getElementsByTagName("id");
        $password = $user->getelementsByTagName("password");
        if($_SESSION['userid']==$userid[0]->nodeValue && $_SESSION['password']==$password[0]->nodeValue)
        {
            header('Location: TicketList.php');
        }
        else
        {
            $errorMessage = "Invalid Username/Password";
        }
    }


}
?>
<html>
    <head>
        <title>Login Page</title>
        <link href="Styles/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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
                        <li><span class="link"><a href="Index.php">Login</a></span></li>
                        <li><span class="link"><a href="Register.php">Register</a></span></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <section id="loginForm">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                            <div class="card card-signin my-5">
                                <div class="card-body">
                                    <h2 class="card-title text-center">Sign In</h2>
                                    <?php if(isset($_GET['userid'])) echo "<p id='success'>Registered Successfully.</p><p id='success'> Your User ID is <span class='green'>".$_GET['userid']."</span></p>";?>
                                    <form method="post">
                                        <div class="form-label-group">
                                            <label for="userid">UserID:</label>
                                            <input type="text" class="form-control" name="userid" id="userid"/>
                                        </div>
                                        <div class="form-label-group">
                                            <label for="password">Password:</label>
                                            <input type="password" class="form-control" name="password" id="password"/>
                                        </div>
                                        <span id="errorMessage"><?=$errorMessage?></span>
                                        <div class="text-center">
                                            <input type="submit" name="submit" id="button" class="btn btn-primary" value="Sign in"/>
                                        </div>
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
