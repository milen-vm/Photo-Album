<div class="container">
    <form action="<?=ROOT_URL?>account/register" method="post" class="form-horizontal">
        <fieldset>
            <!-- Form Name -->
            <legend>
                Register Account
            </legend>
            <!-- Username input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="username">Username</label>
                <div class="col-md-4">
                    <input id="username" name="user_name" placeholder="max length 20 characters"
                        class="form-control input-md" type="text" autofocus="" />
                    <span class="help-block">Usernme for login to yours albums.</span>
                </div>
            </div>
            <!-- First name input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="first-name">First name</label>
                <div class="col-md-4">
                    <input id="first-name" name="first_name" placeholder="max length 20 characters"
                        class="form-control input-md" type="text" />
                    <span class="help-block">First name will be displayed on site.</span>
                </div>
            </div>
            <!-- Last name input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="last-name">Last name</label>
                <div class="col-md-4">
                    <input id="last-name" name="last_name" placeholder="max length 20 characters"
                        class="form-control input-md" type="text" />
                    <span class="help-block">Last name will be displayed on site.</span>
                </div>
            </div>
            <!-- Birth date input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="birth-date">Birth date</label>
                <div class="col-md-4">
                    <input id="birth-date" name="birth_date" placeholder="YYYY-MM-DD"
                        class="form-control input-md" type="date" />
                    <span class="help-block">Birth date is optional.</span>
                </div>
            </div>
            <!-- Email input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">E-mail</label>
                <div class="col-md-4">
                    <input id="email" name="email" placeholder="e-mail"
                        class="form-control input-md" type="email" />
                    <span class="help-block">Enter valid e-mail.</span>
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
            <!-- Confirm password input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="confirm-password">Confirm Password</label>
                <div class="col-md-4">
                    <input id="confirm-password" name="confirm_password" placeholder="confirm password" 
                     class="form-control input-md" type="password" />
                    <span class="help-block">Confirm password.</span>
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label">Submit</label>
                <div class="col-md-4">
                    <input type="hidden" name="form_token" value="<?=$_SESSION['form_token']?>" />
                    <input id="register" type="submit" value="Register" name="submit"
                    class="btn btn-primary" />
                </div>
            </div>
        </fieldset>
    </form>
</div>