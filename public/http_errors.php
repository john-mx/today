<?php
  $HttpStatus = $_SERVER["REDIRECT_STATUS"] ;
  print $HttpStatus . ': ';
  //if($HttpStatus==200) {print "Document has been processed and sent to you.";}
  if($HttpStatus==400) {print "Bad HTTP request ";}
  if($HttpStatus==401) {print "Unauthorized - Iinvalid password";}
  if($HttpStatus==403) {print "Forbidden";}
  if($HttpStatus==404) {print "Page does not exist.  <a href='/index.php'>Go to Index</a>";}
  if($HttpStatus==500) {print "Internal Server Error";}
  if($HttpStatus==418) {print "I'm a teapot! - This is a real value, defined in 1998";}

/*
ErrorDocument 404 /http_errors.php
ErrorDocument 500 /http_errors.php
ErrorDocument 400 /http_errors.php
ErrorDocument 401 /http_errors.php
ErrorDocument 403 /http_errors.php
*/
?>
