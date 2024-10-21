<?
include("../mysql/configuration.php");
include("../mysql/lib.php");
include("../misc/lib.php");
include("../variables.php");
include("../in_out.php");
include("../compute_particularity.php");


/******************************************************************/
//               MAIN IN
/******************************************************************/
$N=17;
if ($login  !="")       $db_user=$login;
if ($ip     !="")       $db_host=$ip;
if ($passwd !="")       $db_password=$passwd;
if ($base   !="")       $db_name=$base;
if ($n      !="")       $N=$n;


/******************************************************************/
//               MAIN OUT
/******************************************************************/
function display_all_operation($title,$tab,$connexion){
    global $graph,$degree,$card_X_sup6,$NoD,$nbNoD;
    $lg=sizeof($tab);
    if ($lg>0) print"<br>$title<br>";else print"<br>$title  enable on all MPG5<br>";
    for($i=0;$i<$lg;$i++){
        unset($graph);unset($degree);unset($X_sup6);$card_X_sup6=0;unset($NoD);$nbNoD=0;
        mysql_graph_loading ($tab[$i],$connexion);
        print "<br>ID=$tab[$i]";
        display_graph ();
        //display_vecteur("Number in Xsup6 : ",$X_sup6,$card_X_sup6);
        }
}


$connexion = connexion ($db_host, $db_user, $db_password,$db_name);

if ($noT_1) {
    $tab1 = select_graph_not_T_1 ($connexion,$N);
   // display_all_operation("OPERATION T-1",$tab1,$connexion);
    
    $lg=sizeof($tab1);
    if ($lg>0) print"<br>T^{-1} disable on following MPG5 with $N vertices<br>";else print"<br>T^{-1} enable on all MPG5 with $N vertices<br>";
    for($i=0;$i<$lg;$i++){
        unset($graph);unset($degree);unset($X_sup6);$card_X_sup6=0;unset($NoD);$nbNoD=0;
        mysql_graph_loading ($tab1[$i],$connexion);
        print "<br>ID=$tab1[$i]";
        display_graph ();
        //display_vecteur("Number in Xsup6 : ",$X_sup6,$card_X_sup6);
        }
    }
if ($noD)   {
    $tab2 = select_graph_not_D ($connexion,$N);
    //display_all_operation("OPERATION D",$tab2,$connexion);
        $lg=sizeof($tab2);
    if ($lg>0) print"<br>D disable on following MPG5 with $N vertices<br>";else print"<br>D enable on all MPG5 with $N vertices<br>";
    for($i=0;$i<$lg;$i++){
        unset($graph);unset($degree);unset($X_sup6);$card_X_sup6=0;unset($NoD);$nbNoD=0;
        mysql_graph_loading ($tab2[$i],$connexion);
        print "<br>ID=$tab2[$i]";
        display_graph ();
        //display_vecteur("Number in Xsup6 : ",$X_sup6,$card_X_sup6);
        }
    }

if ($gdisplay) {
    $all_graphs_id_no_isomorphic_with_N_vertices = looking_for_graphs_no_isomophormic_with_N ($N,$connexion);
    $lg = sizeof($all_graphs_id_no_isomorphic_with_N_vertices);
    for($i=0;$i<$lg;$i++){
        $id_graph = $all_graphs_id_no_isomorphic_with_N_vertices[$i];
        unset($graph);unset($degree);unset($X_sup6);$card_X_sup6=0;unset($NoD);$nbNoD=0;
        mysql_graph_loading ($id_graph,$connexion);
        print "<br>($i/$lg) id=$id_graph";
        display_graph ();
        }
    }
if ($display_id) {
    $all_graphs_id_no_isomorphic_with_N_vertices = looking_for_graphs_no_isomophormic_with_N ($N,$connexion);
    $lg = sizeof($all_graphs_id_no_isomorphic_with_N_vertices);
    for($i=0;$i<$lg;$i++){
        $id_graph = $all_graphs_id_no_isomorphic_with_N_vertices[$i];
        print "<br> ($i/$lg) Id graph = $id_graph";
        }
    }    
    






?>
