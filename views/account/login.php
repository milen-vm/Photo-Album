<div class="container">
    <form action="<?=ROOT_URL?>account/login" method="post" class="form-horizontal">
        <!-- Form Name -->
        <h3>
            Login to Account
        </h3>
        <hr />
        <!-- Email input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="email">Email</label>
            <div class="col-md-4">
                <input id="email" name="email" placeholder="email"
                    class="form-control input-md" type="email" autofocus="" />
                <span class="help-block">Enter email to login in yours albums.</span>
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
    </form>
</div>