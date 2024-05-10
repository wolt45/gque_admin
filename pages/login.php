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
      </div>

      <div class="clearfix"></div>

      <div class="separator">


        <div>
          <h1><i class="fa fa-hospital-o"></i> Gustilo Mobile Medical Records</h1>
          <p>©2021 All Rights Reserved. Privacy and Terms</p>
        </div>
      </div>
    </form>
  </section>
</div>