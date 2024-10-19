<?

// THe Dude

include("data/mpg5_14.php");




include("variables.php");
include("in_out.php");
include("misc/lib.php");
include("check/check.php");
include("operations/flip.php");
include("operations/explosion.php");
include("compute_particularity.php");


/******************************************************************/
//              MAIN
/******************************************************************/


error_reporting (0);
for($nbgraph=1;$nbgraph<=$nbG;$nbgraph++){
    $deb =time();
    $graph = init_graph ($MPG14);
    if ($verbose=="ok")         display_graph();
    while ($N<$Nmax){
        if ($verif_all=="ok")   verification();
        explosion();
        $N++;
        if ($verbose == "ok")   display_graph();
        if ($verbose=="ok")     display_vecteur("Xsup6 vector update: ",$X_sup6,$card_X_sup6);
        flips();
        if (($verbose=="ok")&&($flip=="ok")&&($nbD>0)) print "<br> FLIP $nbD(max=$max_d) operations for $N vertices.";
        }
        
    //____________________________________________________
    // Here we have $N vertices: current graph is finished
    // Here we have to use several options.
    //____________________________________________________
    
    $fin = time();          $delta=$fin-$deb;
    verification();
    if ($matrix=="ok")      display_graph();
    if ($verif=="ok")       verification();
    if ($dimacs=="ok")      dimacs_display ();
    if ($statD=="ok")       display_all_flip();
    if ($time=="ok")        print "\n<br>Time:$delta(s)<br>\n";
    if ($compute_nbD=="ok") {
                            type_B ();
                            display_vecteur("Number of vertice x in Xsup6 with N(x) in Xinf6: ",$NoD,$nbNoD);
                            }
    if ($reduceD=="ok")     {
                            looking_for_egde_eD_with_min_X_NotD_in_D_eD();
                            $i=$edge[1][1];$j=$edge[1][2];$k=$edge[1][3];print "<br>Reduce D: i=$i j=$j k=$k<br>";
                            flip2($i,$j);
                            display_graph();
                            verification();
                            }
    dimacs_url ($url,$nbgraph);
    }
?>
