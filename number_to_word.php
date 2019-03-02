<?php
$nwords = array( "Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven",
                   "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
                   "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                   "Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty",
                   50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty",
                   90 => "Ninety" );

function int_to_words($x) {
   global $nwords;

   if(!is_numeric($x))
      $w = '#';
   else if(fmod($x, 1) != 0)
      $w = '#';
   else {
      if($x < 0) {
         $w = 'minus ';
         $x = -$x;
      } else
         $w = '';
      // ... now $x is a non-negative integer.

      if($x < 21)   // 0 to 20
         $w .= $nwords[$x];
      else if($x < 100) {   // 21 to 99
         $w .= $nwords[10 * floor($x/10)];
         $r = fmod($x, 10);
         if($r > 0)
            $w .= ' '. $nwords[$r];
              } else if($x < 1000) {   // 100 to 999
                 $w .= $nwords[floor($x/100)] .' Hundred';
                 $r = fmod($x, 100);
                 if($r > 0)
                    //$w .= ' and '. int_to_words($r);
                    $w .= ' '. int_to_words($r);
                  } else if($x < 100000) {   // 1000 to 999999
                     $w .= int_to_words(floor($x/1000)) .' Thousand';
                     $r = fmod($x, 1000);
                     if($r > 0) {
                        $w .= ' ';
                        if($r < 100)
                           //$w .= 'and ';
                           $w .= '';
                        $w .= int_to_words($r);
                     }
                       } else if($x < 10000000) {   // 1000 to 999999
                     $w .= int_to_words(floor($x/100000)) .' Lac';
                     $r = fmod($x, 100000);
                     if($r > 0) {
                        $w .= ' ';
                        if($r < 100)
                           //$w .= 'and ';
                           $w .= '';
                        $w .= int_to_words($r);
                     }
                       } else {    //  millions
                             $w .= int_to_words(floor($x/10000000)) .' Crore';
                             $r = fmod($x, 10000000);
                             if($r > 0) {
                                $w .= ' ';
                                if($r < 100)
                                   //$word .= 'and ';
                                   $word .= '';
                                $w .= int_to_words($r);
                             }
                            }
   }
   return $w;
}

?>


<?php
   
//require_once('number_to_word.php');    

//$tk = 90608909;

//echo $tk;

//echo "<br>";

//echo 'Taka '. int_to_words($tk) . ' only';
?>