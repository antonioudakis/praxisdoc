<?php
    print"<table border=0>";
    foreach ($_SERVER as $key=>$val )
       {
         echo "<tr><td>".$key."</td><td>" .$val."</td></tr>";
        }
    print"</table>";
?>