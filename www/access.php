<?php

function valid_password ($pwd) {
$default_pwd ="default";

 if ($pwd == $default_pwd) {
   return true;
 }
 return false;
}

function check_access ($pwd) {

  if (valid_password($pwd)) { 
    return true;
  } else {
    print <<<END
  <h3> Login Required </h3>
    <p>You need a password to view reports.
  <p><form method="post" action="report.php">
    Password: <input type="password" name="pwd" SIZE="8" /><br />
    <input type="submit" value="Log in" />
  </form></p>
END;
    
    return false;
  }

}

?>