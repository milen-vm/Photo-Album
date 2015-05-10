<div class="container">
    <form action="/Photo-Album/account/login" method="post" class="form-horizontal">
        <fieldset>
            <!-- Form Name -->
            <legend>
                Login to Account
            </legend>
            <!-- Username input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="username">Username</label>
                <div class="col-md-4">
                    <input id="username" name="user_name" placeholder="username"
                        class="form-control input-md" type="text" autofocus="" />
                    <span class="help-block">Usernme for login to yours albums.</span>
                </div>
            </div>
            <!-- Password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="password">Password</label>
                <div class="col-md-4">
                    <input id="password" name="password" placeholder="password"
                        class="form-control input-md" type="password" />
                    <span class="help-block">Enter password.</span>
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label">Login</label>
                <div class="col-md-4">
                    <input type="hidden" name="form_token" value="<?=$_SESSION['form_token']?>" />
                    <input id="register" type="submit" value="Login" name="submit"
                        class="btn btn-primary" />
                </div>
            </div>
        </fieldset>
    </form>
</div>