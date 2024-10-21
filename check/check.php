<?

/******************************************************************/
//              MPG5 VERIFICATION valid plan orientation
/******************************************************************/

function local_verif($x,$y,$z,$v,$a,$b){
    // around x we looking for the sequence y and z
    global $graph, $degree, $error, $N;

    for($i=1;$i<=$degree[$x];$i++){
        if ($graph[$x][$i]==$y){
            $k=$i+1;
            if ($k>$degree[$x])  $k=1;
            $proba_z=$graph[$x][$k];
            if ($proba_z!=$z) {
                print "<br>Error : Bad orientation N=$N: init (v=$v,a=$a,b=$b) around x=$x we looking for the sequence y=$y and z=$z !!!<br>";
                $error=-1;
                }
            }
        }

    }

function verification (){
    global $graph, $degree,$N,$error;

    for ($v=1;$v<=$N;$v++){
        for ($i=1;$i<$degree[$v];$i++){
            $a=$graph[$v][$i];
            $b=$graph[$v][$i+1];
            // v give a and b. So 1) a should give b and v 2) b should give v and a. => verification
            local_verif($a,$b,$v,$v,$a,$b); local_verif($b,$v,$a,$v,$a,$b);
            }
        if ((!$error) && ($verbose=="ok")) print "<br>Verication around v=$v OK...";
        }
    if ($error<0) {
        display_graph();
        print "<br> Verification Failed...<br>";
        }
    else
        print "<br> Verification OK... N=$N<br>";
    }

?>
