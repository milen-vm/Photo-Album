<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
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
                    <a class="navbar-brand" href="/Photo-Album"><img src="/Photo-Album/content/images/logo.png" ></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="/Photo-Album/home/index">Home</a>
                        </li>
                        <?php if (!$this->isLoggedIn()) :?>
                            <li>
                                <a href="/Photo-Album/account/register">Register</a>
                            </li>
                            <li>
                                <a href="/Photo-Album/account/login">Login</a>
                            </li>
                        <?php endif ?>
                        <?php if ($this->isLoggedIn()) :?>
                            <li>
                                <a href="/Photo-Album/album">My Albums</a>
                            </li>
                            <li>
                                <a href="/Photo-Album/album/create">Create</a>
                            </li>
                        <?php endif ?>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if ($this->isLoggedIn()) :?>
                            <li>
                                <p id="full-name" class="navbar-text">
                                    Wellcome, <?php echo $this->getFullName(); ?>
                                </p>
                            </li>
                            <li>
                                <a id="logout" href="#">Logout</a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>
        <?php include_once 'messages.php'; ?>
        <script>
            $('#logout').on('click', function(ev) {
                $.ajax({
                    method: 'POST',
                    url: '/Photo-Album/account/logout',
                    success: function(data) {
                        location.reload(); 
                    },
                    error: function() {
                        console.log('Cannot load AJAX data.');
                    }
                })
            });
        </script>