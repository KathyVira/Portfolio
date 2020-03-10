<?php

session_start();

// function getRealIp()
// {
//     if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
//         $ip = $_SERVER['HTTP_CLIENT_IP'];
//     } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
//         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//     } else {
//         $ip = $_SERVER['REMOTE_ADDR'];
//     }
//     return $ip;
// }


// function writeLog($where)
// {

//     $ip = getRealIp(); // Get the IP from superglobal
//     $host = gethostbyaddr($ip);    // Try to locate the host of the attack
//     $date = date("d M Y");

//     // create a logging message with php heredoc syntax
//     $logging = <<<LOG
//         \n
//         << Start of Message >>
//         There was a hacking attempt on your form. \n 
//         Date of Attack: {$date}
//         IP-Adress: {$ip} \n
//         Host of Attacker: {$host}
//         Point of Attack: {$where}
//         << End of Message >>
// LOG;
//     // Awkward but LOG must be flush left

//     // open log file
//     if ($handle = fopen('hacklog.log', 'a')) {

//         fputs($handle, $logging);  // write the Data to file
//         fclose($handle);           // close the file

//     } else {  // if first method is not working, for example because of wrong file permissions, email the data

//         $to = 'kathyvira@gmail.com';
//         $subject = 'HACK ATTEMPT';
//         $header = 'From: kathyvira@gmail.com';
//         if (mail($to, $subject, $logging, $header)) {
//             echo "Sent notice to admin.";
//         }
//     }
// }

function verifyFormToken($form)
{

    // check if a session is started and a token is transmitted, if not return an error
    if (!isset($_SESSION[$form . '_token'])) {
        return false;
    }

    // check if the form is sent with token in it
    if (!isset($_POST['token'])) {
        return false;
    }

    // compare the tokens against each other if they are still the same
    if ($_SESSION[$form . '_token'] !== $_POST['token']) {
        return false;
    }

    return true;
}

function generateFormToken($form)
{

    // generate a token from an unique value, took from microtime, you can also use salt-values, other crypting methods...
    $token = md5(uniqid(microtime(), true));

    // Write the generated token to the session variable to check it against the hidden field when the form is sent
    $_SESSION[$form . '_token'] = $token;

    return $token;
}

// // VERIFY LEGITIMACY OF TOKEN
// if (verifyFormToken('form1')) {

//     // CHECK TO SEE IF THIS IS A MAIL POST
//     if (isset($_POST['URL-main'])) {

//         // Building a whitelist array with keys which will send through the form, no others would be accepted later on
//         $whitelist = array('token', 'req-name', 'req-email', 'req-subject', 'req-message');

//         // Building an array with the $_POST-superglobal 
//         foreach ($_POST as $key => $item) {

//             // Check if the value $key (fieldname from $_POST) can be found in the whitelisting array, if not, die with a short message to the hacker
//             if (!in_array($key, $whitelist)) {

//                 writeLog('Unknown form fields');
//                 die("Hack-Attempt detected. Please use only the fields in the form");
//             }
//         }






//         // Lets check the URL whether it's a real URL or not. if not, stop the script

//         if (!filter_var($_POST['URL-main'], FILTER_VALIDATE_URL)) {
//             writeLog('URL Validation');
//             die('Hack-Attempt detected. Please insert a valid URL');
//         }





//         // SAVE INFO AS COOKIE, if user wants name and email saved

//         $saveCheck = $_POST['save-stuff'];
//         if ($saveCheck == 'on') {
//             setcookie("WRCF-Name", $_POST['req-name'], time() + 60 * 60 * 24 * 365);
//             setcookie("WRCF-Email", $_POST['req-email'], time() + 60 * 60 * 24 * 365);
//             setcookie("WRCF-Subject", $_POST['req-subject'], time() + 60 * 60 * 24 * 365);
//             setcookie("WRCF-Message", $_POST['req-message'], time() + 60 * 60 * 24 * 365);
//         }




//         // PREPARE THE BODY OF THE MESSAGE

//         $message = '<html><body>';

//         $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';

//         $message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags($_POST['req-name']) . "</td></tr>";

//         $message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($_POST['req-email']) . "</td></tr>";

//         $message .= "<tr><td><strong>Subject:</strong> </td><td>" . strip_tags($_POST['req-subject']) . "</td></tr>";
//         $message .= "<tr><td><strong>Message:</strong> </td><td>" . strip_tags($_POST['req-message']) . "</td></tr>";


//         $message .= "</table>";
//         $message .= "</body></html>";




//         //  MAKE SURE THE "FROM" EMAIL ADDRESS DOESN'T HAVE ANY NASTY STUFF IN IT

//         $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
//         if (preg_match($pattern, trim(strip_tags($_POST['req-email'])))) {
//             $cleanedFrom = trim(strip_tags($_POST['req-email']));
//         } else {
//             return "The email address you entered was invalid. Please try again!";
//         }




//         //   CHANGE THE BELOW VARIABLES TO YOUR NEEDS

//         $to = 'kathuvira@gmail.com';

//         $subject = 'kathyvira.com';

//         $headers = "From: " . $cleanedFrom . "\r\n";
//         $headers .= "Reply-To: " . strip_tags($_POST['req-email']) . "\r\n";
//         $headers .= "MIME-Version: 1.0\r\n";
//         $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

//         if (mail($to, $subject, $message, $headers)) {
//             echo 'Your message has been sent.';
//         } else {
//             echo 'There was a problem sending the email.';
//         }

//         // DON'T BOTHER CONTINUING TO THE HTML...
//         die();
//     }
// } else {

//     if (!isset($_SESSION[$form . '_token'])) {
//     } else {
//         echo "Hack-Attempt detected. Got ya!.";
//         writeLog('Formtoken');
//     }
// }



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kathy Vira</title>
    <!-- Google Font -->
    <!-- <link h
 -->

    <!-- Font Awesome -->

    <script src="https://kit.fontawesome.com/f7c313ecd4.js" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> -->

    <!-- Preloader -->
    <link rel="stylesheet" href="css/preloader.css" type="text/css" media="screen, print" />

    <!-- Icon Font-->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.default.css">
    <!-- Animate CSS-->
    <link rel="stylesheet" href="css/animate.css">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">



    <!-- Style -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Responsive CSS -->
    <link href="css/responsive.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!-- <[if lt IE 9]> -->
    <!-- <script src="js/lte-ie7.js"></script>
	  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script> -->
    <!-- <![endif]> -->

    <!-- mail connect -->
    <script src="http://www.google.com/jsapi" type="text/javascript"></script>
    <script type="text/javascript">
        google.load("jquery", "1.3.2");
    </script>
    <script type="text/javascript" src="js/jquery.jqtransform.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.form.js"></script>

    <script type="text/javascript" src="js/websitechange.js"></script>
</head>
<?php
// generate a new token for the $_SESSION superglobal and put them in a hidden field
$newToken = generateFormToken('form1');
?>

<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>




    <header id="HOME" style="background-position: 50% -125px;">
        <div class="section_overlay">
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"><img src="images/logo.png" alt=""></a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#HOME">Home</a></li>
                            <li><a href="#SERVICE">Services</a></li>
                            <li><a href="#ABOUT">About</a></li>
                            <li><a href="#TESTIMONIAL">Testimonial</a></li>
                            <li><a href="#WORK">Work</a></li>
                            <li><a href="#CONTACT">Contact</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container -->
            </nav>

            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="home_text wow fadeInUp animated">
                            <h2>Full Stack Developer Portfolio</h2>
                            <p>Hello my name is Kathy Vira </p>
                            <img src="images/shape.png" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="scroll_down">
                            <a href="#SERVICE"><img src="images/scroll.png" alt=""></a>
                            <h4>Scroll Down</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </header>


    <section class="services" id="SERVICE">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="single_service wow fadeInUp" data-wow-delay="1s">
                        <i class="icon-pencil"></i>
                        <h2>Design</h2>
                        <div class="text-left">
                            <h4> A several years experience in print and web designs </h4>
                            <p>WEB: Angular, HTML, CSS, Bootstrap, JavaScript,</p>
                            <p> jQuery, FontAwesome</p>

                        </div>

                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="single_service wow fadeInUp" data-wow-delay="2s">
                        <i class="icon-gears"></i>
                        <h2>Development</h2>
                        <div class="text-left">
                            <h4>Learning and Experience of web Sites with Backend</h4>
                            <p>Server Side Script: PHP, TypeScript </p>
                            <!-- Node.js -->
                            <p>Framework: Laravel, WordPress, Angular, React. </p>
                            <p>DataBase: MySQL, MongoDB, FireBase.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="single_service wow fadeInUp" data-wow-delay="3s">
                        <i class="icon-camera"></i>
                        <h2>Photography</h2>
                        <div class="text-left">
                            <h4>Photo processing and album design</p>
                                <p>Print: Photoshop, Illustrator, Lightroom, 3DMax, </p>
                                <p>InDesign </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3 text-center">
                    <div class="single_service wow fadeInUp" data-wow-delay="4s">
                        <i class="icon-magnifying-glass"></i>
                        <h2>Seo</h2>
                        <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa.</p>
                    </div> 

                </div> -->
        </div>
    </section>
    <section class="about_us_area" id="ABOUT">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="about_title">
                        <h2>About Me</h2>
                        <img src="images/shape.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-4  wow fadeInLeft animated">
                    <div class="single_progress_bar">
                        <h2>DESIGN - 70%</h2>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                                <span class="sr-only">70% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="single_progress_bar">
                        <h2>DEVELOPMENT - 70%</h2>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                                <span class="sr-only">70% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="single_progress_bar">
                        <h2>Photografy - 35%</h2>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 35%;">
                                <span class="sr-only">35% Complete</span>
                            </div>
                        </div>
                    </div>
                    <h4> Languages</h4>
                    <div class="single_progress_bar">
                        <h2>Hebrew - 95%</h2>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 95%;">
                                <span class="sr-only">95% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="single_progress_bar">
                        <h2>English - 70%</h2>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
                                <span class="sr-only">70% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="single_progress_bar">
                        <h2>Russian - 100%</h2>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                <span class="sr-only">100% Complete</span>
                            </div>
                        </div>
                    </div>

                    <!-- <table class="table">
                        <h4> Languages</h4>
                        <tr>
                            <td scope="row">
                                Hebrew
                            </td>
                            <td scope="row">
                                Exelent
                            </td>

                        </tr>
                        <tr>
                            <td scope="row">
                                English
                            </td>
                            <td scope="row">
                                Good
                            </td>

                        </tr>
                        <tr>
                            <td scope="row">
                                Russian
                            </td>
                            <td scope="row">
                                Exelent
                            </td>

                        </tr>
                    </table> -->
                    <!-- <div class="single_progress_bar">
                        <h2>SEO - 95%</h2>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100" style="width: 95%;">
                                <span class="sr-only">60% Complete</span>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="col-md-4  wow fadeInRight animated">
                    <p class="about_us_p">Driven Web Developer welcoming challenging projects and enjoying working with
                        all sorts of
                        personalities.
                        <h4>Experienced in scripting and cybersecurity as well as design patterns.</h4>
                        <p>

                            Motivated to meet customer and user expectations with high-quality and effective
                            website layouts.
                            Skilled in validating, debugging and correcting code.
                            Newly graduated web developer offering enthusiasm and understanding of various
                            programming languages.
                        </p>
                    </p>
                </div>
                <div class="col-md-4  wow fadeInRight animated">
                    <p class="about_us_p">Looking to join organization where opportunity for growth and professional
                        development is embraced
                        I will do my best to bring value to any team and company I get engaged with.</p>
                    <div class="baton">
                        <a href="">
                            <button type="button" class="btn btn-primary cs-btn">Professional CV</button>
                        </a>
                    </div>
                    <div class="baton">
                        <a href="">
                            <button type="button" class="btn btn-primary cs-btn">Biographical CV</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="testimonial text-center wow fadeInUp animated" id="TESTIMONIAL">
        <div class="container">

            <div class="owl-carousel">
                <div class="single_testimonial text-center wow fadeInUp animated">
                    <p>Self-motivated work ethic <br>
                        Excellent communication skills </p>
                    <h4>-Excellent</h4>
                </div>
                <div class="single_testimonial text-center">
                    <p>Fast learner <br>
                        Unusual ideas and creative insights </p>
                    <h4>-Excellent</h4>
                </div>
            </div>
        </div>
    </section>


    <div class="fun_facts">
        <section class="header parallax home-parallax page" id="fun_facts" style="background-position: 50% -150px;">
            <div class="section_overlay">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 wow fadeInLeft animated">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="single_count">
                                        <i class="icon-toolbox"></i>
                                        <h3>7</h3>
                                        <p>Project Done</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="single_count">
                                        <i class="icon-clock"></i>
                                        <h3>700+</h3>
                                        <p>Study Hours</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="single_count">
                                        <i class="icon-trophy"></i>
                                        <h3>200</h3>
                                        <p>Printed albums</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-1 wow fadeInRight animated">
                            <div class="imac">
                                <img src="images/imac.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <section class="work_area" id="WORK">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="work_title  wow fadeInUp animated">
                        <h1>Latest Works</h1>
                        <img src="images/shape.png" alt="">
                        <p>On this site, I demonstrate My personal qualities and my other abilities</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 no_padding">
                    <div class="single_image">
                        <img src="images/w1.jpg" alt="">
                        <div class="image_overlay">
                            <a href="http://ponpons.kathyvira.com/">View Full Project</a>
                            <h2>Laravel Shop</h2>
                            <h4>PHP backend with Admin panel</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 no_padding">
                    <div class="single_image">
                        <img src="images/w2.jpg" alt="">
                        <div class="image_overlay">
                            <a href="http://blog.kathyvira.com/">View Full Project</a>
                            <h2>Php native</h2>
                            <h4> In this example, write a PHP NATIVE MYSQL blog if you can log in and add
                                posts.</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 no_padding">
                    <div class="single_image">
                        <img src="images/w3.jpg" alt="">
                        <div class="image_overlay">
                            <a href="http://crmAngular.kathyvira.com/">View Full Project</a>
                            <h2>Angular Node.js</h2>
                            <h4>In this example, the frontend is registered in ANGULAR and the backend
                                in NODE.JS </h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row pad_top"> -->
            <!-- <div class="col-md-4 no_padding">
                    <div class="single_image">
                        <img src="images/w4.jpg" alt="">
                        <div class="image_overlay">
                            <a href="">View Full Project</a>
                            <h2>drawing</h2>
                            <h4>with pencil colors</h4>
                        </div>
                    </div>
                </div> -->
            <!-- <div class="col-md-4 no_padding">
                    <div class="single_image">
                        <img src="images/w5.jpg" alt="">
                        <div class="image_overlay">
                            <a href="">View Full Project</a>
                            <h2>drawing</h2>
                            <h4>with pencil colors</h4>
                        </div>
                    </div>
                </div> -->
            <!-- <div class="col-md-4 no_padding">
                    <div class="single_image last_padding">
                        <img src="images/w6.jpg" alt="">
                        <div class="image_overlay">
                            <a href="">View Full Project</a>
                            <h2>drawing</h2>
                            <h4>with pencil colors</h4>
                        </div>
                    </div>
                </div> -->
            <!-- </div> -->
        </div>
    </section>
    <section class="call_to_action">
        <div class="container">
            <div class="row">
                <div class="col-md-8 wow fadeInLeft animated">
                    <div class="left">
                        <h2> I will do my best to bring value to any team and company I get engaged with.</h2>
                        <p>Looking to join organization where opportunity for growth and professional
                            development is embraced</p>
                    </div>
                </div>
                <div class="col-md-3 col-md-offset-1 wow fadeInRight animated">
                    <div class="baton">
                        <a href="#CONTACT">
                            <button type="button" class="btn btn-primary cs-btn">Let's Talk</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="contact" id="CONTACT">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="contact_title  wow fadeInUp animated">
                        <h1>get in touch</h1>
                        <img src="images/shape.png" alt="">
                        <p></p>
                    </div>
                </div>
            </div>
        </div>


        <div class="container">
            <div class="row">


                <div class="col-md-3  wow fadeInLeft animated">
                    <div class="single_contact_info">
                        <h2>Call Me</h2>
                        <p>+972 545 216 426</p>
                    </div>
                    <div class="single_contact_info">
                        <h2>Email Me</h2>
                        <p> <a href="mailto:kathyVira@gmail.com">kathyVira@gmail.com</a></p>
                    </div>
                    <div class="single_contact_info">
                        <h2>Address</h2>
                        <p>Israel</p>
                    </div>
                </div>


                <div class="col-md-9  wow fadeInRight animated">
                    <form id="change-form" class="contact-form" action="index.php" method="post" enctype="text/plain">
                        <input type="hidden" name="token" value="<?php echo $newToken; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control required" placeholder="Name" id="req-name" name="req-name" minlength="2" value="<?php echo $_COOKIE["WRCF-Name"]; ?>" />

                                <input type="email" class="form-control required email" id="email" placeholder="Email" name="req-email" value="<?php echo $_COOKIE["WRCF-Email"]; ?>" />

                                <input type="text" class="form-control required" id="subject" placeholder="Subject" name="req-subject" value="<?php echo $_COOKIE["WRCF-Subject"]; ?>">
                            </div>
                            <div class="col-md-6">
                                <textarea class="form-control" id="message" rows="25" cols="10" placeholder="  Message Texts..." name="req-message" value="<?php echo $_COOKIE["WRCF-Message"]; ?>"></textarea>


                                <button type="submit" name="submit" class="btn btn-default submit-btn form_submit" <a href="https://www.kathyvira.com">SEND
                                    MESSAGE</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="work-with   wow fadeInUp animated">
                        <h3>looking forward to hearing from you!</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <footer>
        <div class="container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="footer_logo   wow fadeInUp animated">
                            <img src="images/logo.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center   wow fadeInUp animated">
                        <div class="social">
                            <h2>Follow Me on Here</h2>
                            <ul class="icon_list">
                                <li><a href="https://www.facebook.com/kat2002"><i class="fab fa-facebook-f"></i></a>
                                </li>
                                <!-- <li><a href=""><i class="fa fa-twitter"></i></a></li> -->
                                <li><a href="https://github.com/KathyVira"><i class="fab fa-github"></i></a></li>
                                <!-- <li><a href=""><i class="fa fa-google-plus"></i></a></li> -->
                                <li><a href="https://www.linkedin.com/in/katy-virabiants-4a6920ba/"><i class="fab fa-linkedin-in"></i></a></li>
                                <!-- <li><a href=""><i class="fa fa-dribbble"></i></a></li> -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="copyright_text   wow fadeInUp animated">
                            <p>&copy; Kathy Vira<a href="" target="_blank"></a></p>
                            <p>Full Stack Developer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>



    <!-- =========================
     to email
============================== -->

    <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
        var pageTracker = _gat._getTracker("UA-68528-29");
        pageTracker._initData();
        pageTracker._trackPageview();
    </script>






    <!-- =========================
     SCRIPTS 
============================== -->


    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/wow.js"></script>
    <script src="js/script.js"></script>




</body>

</html>