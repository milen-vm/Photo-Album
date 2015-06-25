<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?php if (isset($this->title)) {
                 echo 'Photo Album - ' . htmlspecialchars($this -> title);
            } ?>
        </title>
        <link rel="shortcut icon" href="<?=ROOT_URL?>content/images/logo.gif" type="image/x-icon"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://bootswatch.com/cerulean/bootstrap.min.css">
        <link rel="stylesheet" href="<?=ROOT_URL?>content/styles/styles.css" />
        <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="<?=ROOT_URL?>content/js/logout.js"></script>
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
                    <a class="navbar-brand" href="<?=ROOT_URL?>"><img src="<?=ROOT_URL?>content/images/logo.gif" ></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="<?=ROOT_URL?>home/index">Home</a>
                        </li>
                        <?php if (!$this->isLoggedIn()) :?>
                            <li>
                                <a href="<?=ROOT_URL?>account/register">Register</a>
                            </li>
                            <li>
                                <a href="<?=ROOT_URL?>account/login">Login</a>
                            </li>
                        <?php endif ?>
                        <?php if ($this->isLoggedIn()) :?>
                            <li>
                                <a href="<?=ROOT_URL?>album">My Albums</a>
                            </li>
                            <li>
                                <a href="<?=ROOT_URL?>album/create">Create</a>
                            </li>
                        <?php endif ?>
                    </ul>
                    <?php if ($this->isLoggedIn()) :?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php echo $this->getFullName(); ?> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a id="logout" href="">Logout</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    <?php endif ?>
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>
        <?php include_once 'messages.php'; ?>