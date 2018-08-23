<div>
  <a class="hiddenanchor" id="signup"></a>
  <a class="hiddenanchor" id="signin"></a>

  <div class="login_wrapper">
    <div class="animate form login_form">
      <section class="login_content">
        <!--  <form action="adminlogin.php" method="post" name="login" onSubmit="return validateForms()">
              <fieldset>
                  <div class="form-group">
                      <input class="form-control" placeholder="E-mail" name="username" type="text" autofocus>
                  </div>
                  <div class="form-group">
                      <input class="form-control" placeholder="Password" name="password" type="password" value="">
                  </div>
                  <input class="btn btn-lg btn-success btn-block" type="submit" value="Log In">
              </fieldset>
          </form> -->
          <form >
            <h1>GMMR Central</h1>
            <div>
              <input type="text" name="username" class="form-control" placeholder="Username" required="" ng-model="username"/>
            </div>
            <div>
              <input type="password" name="password" class="form-control" placeholder="Password" required="" ng-model="password"/>
            </div>
            <div>
             <button class="btn btn-lg btn-primary btn-block submit" type="submit" ng-click="LoginAccount(username, password)">
                Login
              </button>
            </div>

            <div class="clearfix"></div>

            <div class="separator">
              <p class="change_link">New?
                <a href="#signup" class="to_register"> Create Account </a>
              </p>

              <div class="clearfix"></div>
              <br />

              <div>
                <h1><i class="fa fa-hospital-o"></i> Gustilo Mobile Medical Records</h1>
                <p>©2018 All Rights Reserved. Privacy and Terms</p>
              </div>
            </div>
          </form>
        <!-- <form ng-submit="formSubmit()">
          <h1>GMMR Login</h1>
          <div>
            <input type="text" ng-model="username" class="form-control" placeholder="Username" required="" />
          </div>
          <div>
            <input type="password" ng-model="password" class="form-control" placeholder="Password" required="" />
          </div>
          <div>
            <button class="btn btn-default submit" type="submit" >Login</button>
          </div>

          <div class="clearfix"></div>

          <div class="separator">
            <p class="change_link">New?
              <a href="#signup" class="to_register"> Create Account </a>
            </p>

            <div class="clearfix"></div>
            <br />

            <div>
              <h1><i class="fa fa-hospital-o"></i> Gustilo Mobile Medical Records</h1>
              <p>©2018 All Rights Reserved. Privacy and Terms</p>
            </div>
          </div>
        </form> -->
      </section>
    </div>

    <div id="register" class="animate form registration_form">
      <section class="login_content">
        <form>
          <h1>Create Account</h1>
          <div>
            <input type="text" class="form-control" placeholder="Username" required="" />
          </div>
          <div>
            <input type="email" class="form-control" placeholder="Email" required="" />
          </div>
          <div>
            <input type="password" class="form-control" placeholder="Password" required="" />
          </div>
          <div>
            <a class="btn btn-default submit" href="index.html">Submit</a>
          </div>

          <div class="clearfix"></div>

          <div class="separator">
            <p class="change_link">Already a member ?
              <a href="#signin" class="to_register"> Log in </a>
            </p>

            <div class="clearfix"></div>
            <br />

            <div>
              <h1><i class="fa fa-hospital-o"></i> Gustilo Mobile Medical Records</h1>
              <p>©2018 All Rights Reserved. Privacy and Terms</p>
            </div>
          </div>
        </form>
      </section>
    </div>
  </div>
</div>