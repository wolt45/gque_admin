<div>
  <section class="login_content">
    <form>
      <h1>GMMR-Central Login</h1>
      <div>
        <input type="text" class="form-control" placeholder="Username" required="" ng-model="username"/>
      </div>
      <div>
        <input type="password" class="form-control" placeholder="Password" required="" ng-model="userpassword"/>
      </div>
      <div class="red">{{errorMessage}}</div>
      <div class="green">{{successMessage}}</div>
      
      <div>
        <button class="btn btn-primary submit" ng-click="login(username, userpassword)">
          Login
        </button>
        <a class="reset_pass" href="#">Lost your password?</a>
      </div>

      <div class="clearfix"></div>

      <div class="separator">
        <p class="change_link">New to site?
          <a ui-sref="register" class="to_register"> Create Account </a>
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