<?php
session_start();

$validationFlag=-1;
$firstName = $middleName = $lastName = $email = $password = $country = "";
$errorMessage = $firstNameError = $lastNameError = $emailIdError = $countryError = $passwordError= "";

//Code to validate the form and to add a new user into users.xml
if(isset($_POST['submit']))
{

    $validationFlag=0;
    $firstName = $_POST['firstname'];
    $middleName = $_POST['middlename'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $country = $_POST['country'];
    $userId = 0;
    $currentId=0;
    if($firstName=="") {
        $firstNameError = "Please enter your first name";
        $validationFlag = 1;
    }
    if($lastName=="") {
        $lastNameError = "Please enter your last name";
        $validationFlag = 1;
    }

    if($email == "") {
        $emailIdError = "Please enter your email Id";
        $validationFlag = 1;
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $emailIdError = "Invalid: Please enter a valid email";
            $validationFlag = 1;
        }
    }

    if($password=="") {
        $passwordError = "Please enter your password";
        $validationFlag = 1;
    }

    if($country=="") {
        $countryError = "Please enter your country";
        $validationFlag = 1;
    }

    //Getting the list of users from the user.xml for validation.
    $doc = new DOMDocument('1.0', "utf-8");
    $doc->load("XML/users.xml");

    $users = $doc->getElementsByTagName("user");
    foreach($users as $user)
    {
        $currentId = $user->getElementsByTagName('id')[0]->nodeValue;
        if($currentId > $userId)
        {
            $userId = $currentId;
        }
        if($email==$user->getElementsByTagName('email')[0]->nodeValue)
        {
            $validationFlag = 1;
            $emailIdError = "Email already exists";
        }
    }
    $userId++;

    if($validationFlag==0) {
        $user = $doc->createElement("user");
        $user->setAttribute("type","Client");
        $id = $doc->createElement("id",$userId);
        $password = $doc->createElement("password",$password);
        $name = $doc->createElement("name");
        $firstName = $doc->createElement("firstname",$firstName);
        $middleName = $doc->createElement("middlename",$middleName);
        $lastName = $doc->createElement("lastname",$lastName);
        $name->appendChild($firstName);
        $name->appendChild($middleName);
        $name->appendChild($lastName);
        $country = $doc->createElement("country",$country);
        $email = $doc->createElement("email",$email);
        $user->appendChild($id);
        $user->appendChild($password);
        $user->appendChild($name);
        $user->appendChild($country);
        $user->appendChild($email);
        $users = $doc->getElementsByTagName('users')[0];
        $users->appendChild($user);
        $doc->save("XML/users.xml");
        header('location:Index.php?userid='.$userId);
    }
}
?>

<html>
<head>
    <title>Register Page</title>
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
                            <h2 class="card-title text-center">Register</h2>
                            <form method="post">
                                <div class="form-label-group">
                                    <label for="firstname">First Name:</label>
                                    <input type="text" class="form-control" name="firstname" id="firstname" value="<?=$firstName ?>"/>
                                    <span id="errorMessage"><?=$firstNameError?></span>
                                </div>
                                <div class="form-label-group">
                                    <label for="middlename">Middle Name:</label>
                                    <input type="text" class="form-control" name="middlename" id="middlename" value="<?=$middleName ?>"/>
                                </div>
                                <div class="form-label-group">
                                    <label for="lastname">Last Name:</label>
                                    <input type="text" class="form-control" name="lastname" id="lastname" value="<?=$lastName ?>"/>
                                    <span id="errorMessage"><?=$lastNameError?></span>
                                </div>
                                <div class="form-label-group">
                                    <label for="email">Email:</label>
                                    <input type="text" class="form-control" name="email" id="email" value="<?=$email ?>"/>
                                    <span id="errorMessage"><?=$emailIdError?></span>
                                </div>
                                <div class="form-label-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" name="password" id="password" value="<?=$password ?>"/>
                                    <span id="errorMessage"><?=$passwordError?></span>
                                </div>
                                <div class="form-label-group">
                                    <label for="Country">Country</label>
                                    <select class="form-control" id="country" name="country">
                                        <option value="Canada" <?php if($country=="Canada") echo "selected";?>>Canada</option>
                                        <option value="USA" <?php if($country=="USA") echo "selected";?>>USA</option>
                                    </select>
                                    <span id="errorMessage"><?=$countryError?></span>
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

