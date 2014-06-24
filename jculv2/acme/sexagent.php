<?php

function agent ()
{
   $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
   $ipad   = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
   $iPod   = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");

   if ($iphone == true){
    echo '278';
  }
   elseif ($ipad == true){
    echo '600';
  }
   elseif ($iPod == true){
    echo '278';
  }
   else{
    echo '900';
  }

}

