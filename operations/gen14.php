<?
include("data/mpg5_14.php");
include("in_out.php");
include("operations/flip.php");
include("operations/explosion.php");
include("compute_particularity.php");


/******************************************************************/
//              BASICS VARIABLES
/******************************************************************/
$error="";
$graph[][]="";
$nbflip[]="";
$v=0;$v1=0;$v2=0;
$MAX_TRY_D = 5;


/*
    v1
N       v
    v2
*/

//----------------------------------------------------------//
// Nv : first neighborhood of current vertex v to explose   //
// Nv1 : first neighborhood of v after explosion            //
// Nv2 : first neighborhood of N+1 after explosion          //
//----------------------------------------------------------//
$Nv[]="";$Nv1="";$Nv2[]="";


/**************************/
//           FLIP OPERATION
/**************************/
/*
    c
a<------>b
    d

*/

$a=0;$b=0;$c=0;$d=0; // four vertices in order to use diagonal operation

/******************************************************************/
//              INIT GRAPH
/******************************************************************/


function init_graph ($starting_graph){
    global $N, $degree,$graph,$X_sup6,$card_X_sup6,$card_X_inf6;

    $N=14;
    $X_sup6 [1]=1; $X_sup6 [2]=8;//=array(1,8);
    $card_X_sup6=2;$degree[1]=6;$degree[8]=6;
    $X_inf6 =array(2,3,4,5,6,7,9,10,11,12,13,14);
    $card_X_inf6=12;
    $degree[2]=5;$degree[3]=5;$degree[4]=5;$degree[5]=5;
    $degree[6]=5;$degree[7]=5;$degree[9]=5;$degree[10]=5;
    $degree[11]=5;$degree[12]=5;$degree[13]=5;$degree[14]=5;

    for ($i=1;$i<=$N;$i++){
        $dg=$degree[$i];
        for($j=1;$j<=$degree[$i];$j++)
            $graph[$i][$j]=$starting_graph[$i][$j];
        }
    return $graph;
    }
/******************************************************************/
//              UPDATE Xsup6
/******************************************************************/

/*
function Xsup6_add_vertex($x,$new_vertice){
    global $X_sup6, $card_X_sup6, $verbose, $degree;
    if ((($new_vertice==TRUE)&&($degree[$x]>5))||(($new_vertice==FALSE)&&($degree[$x]==6))){
        $card_X_sup6++;
        $X_sup6[$card_X_sup6]=$x;
        if ($verbose=="ok") print "<br>Update X_sup6: add vertex $x degree $degree[$x] in last position.";
        }
}
*/

function Xsup6_add_vertex($x, $old_degree_of_x){
    global $X_sup6, $card_X_sup6, $verbose, $degree;
    if (($old_degree_of_x<=5)&&($degree[$x]>5)){
        $card_X_sup6++;
        $X_sup6[$card_X_sup6]=$x;
        if ($verbose=="ok") print "<br>Update X_sup6: add vertex $x degree $degree[$x] in last position.";
        }
}

function Xsup6_del_vertex($x){
    global $X_sup6, $card_X_sup6, $verbose, $degree;
    if ($degree[$x]<=5){
        $j=1;
        for($i=1;$i<=$card_X_sup6;$i++)
            if ($X_sup6[$i] !=$x ) {$tmp[$j]=$X_sup6[$i];$j++;}
        for ($i=1;$i<=$j-1;$i++)$X_sup6[$i]=$tmp[$i];
    
        $card_X_sup6=$j-1;
        if ($verbose=="ok") print "<br>Update X_sup6: del vertex $x degree $degree[$x].";
        }
}

    
    
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
    


function neighbord($x,$y){
    global $graph, $degree;
    
    $a=$y;$b=$x;
    if ($degree[$x]<$degree[$y]){$a=$x;$b=$y;}
    for ($i=1;$i<=$degree[$a];$i++)
        if ($graph[$a][$i]==$b)  return (TRUE);
    return (FALSE);

}

/******************************************************************/
//              MAIN
/******************************************************************/


error_reporting (0);
for($nbgraph=1;$nbgraph<=$nbG;$nbgraph++){
    $deb =time();
    $graph = init_graph ($MPG14);
    if ($verbose=="ok") display_graph();
    
    while ($N<$Nmax){
        if ($verif_all=="ok") verification();
        explosion();
        $N++;
        
        if ($verbose == "ok") display_graph();
        if ($verbose=="ok") display_vecteur("Xsup6 vector update: ",$X_sup6,$card_X_sup6);
        
        flips();
        if (($verbose=="ok")&&($flip=="ok")&&($nbD>0)) print "<br> FLIP $nbD(max=$max_d) operations for $N vertices.";
        
        }
    $fin = time();$delta=$fin-$deb;
    
    verification();
    if ($matrix=="ok")  display_graph();
    if ($verif=="ok")   verification();
    if ($dimacs=="ok")  dimacs_display ();
    if ($statD=="ok")   display_all_flip();
    if ($time=="ok")    print "\n<br>Time:$delta(s)<br>\n";
    dimacs_url ($url,$nbgraph);
    }
?>
