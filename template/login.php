    <div class="content">
      <div class="row">
        <div class="login-form">
          <h2>Login</h2>
          <form action="<?php echo str_replace("\\", "/", dirname($_SERVER['SCRIPT_NAME']));?>wiki/" method="Post">
            <fieldset>
              <div class="clearfix">
                <input type="text" placeholder="Username" name="username">
              </div>
              <div class="clearfix">
                <input type="password" placeholder="Password" name="password">
              </div>
              <button class="btn primary" type="submit">Sign in</button>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
