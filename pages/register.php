<div id="register" >
  <section class="login_content">
    <form>
      <h1>Create Account</h1>
      <div>
        <input type="text" class="form-control" placeholder="Username" required="" ng-model="username"/>
      </div>
      <div>
        <input type="email" class="form-control" placeholder="Email" required="" ng-model="username" ng-model="userEmail"/>
      </div>
      <div>
        <input type="password" class="form-control" placeholder="Password" required="" ng-model="userPassqord"/>
      </div>
      <div>
        <a class="btn btn-default submit" href="index.html">Submit</a>
      </div>

      <div class="clearfix"></div>

      <div class="separator">
        <p class="change_link">Already a member ?
          <a ui-sref="login" class="to_register"> Log in </a>
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