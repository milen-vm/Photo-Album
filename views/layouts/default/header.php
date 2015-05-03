<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>
            <?php if (isset($this->title)) {
                 echo htmlspecialchars($this -> title);
            } ?>
        </title>
        <link rel="shortcut icon" href="/Photo-Album/content/images/logo.png" type="image/x-icon"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css"> -->
        <link rel="stylesheet" href="http://bootswatch.com/cerulean/bootstrap.min.css">
        <link rel="stylesheet" href="/Photo-Album/content/styles/styles.css" />
        
    </head>
    <body>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="/Photo-Album/content/images/logo.png" ></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="/Photo-Album/home/index">Home</a>
                        </li>
                        <li>
                            <a href="/Photo-Album/account/register">Register</a>
                        </li>
                        <li>
                            <a href="#">Login</a>
                        </li>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#">Logout</a>
                        </li>
                        
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>