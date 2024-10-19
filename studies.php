<?
include("./mysql/configuration.php");
include("./mysql/lib.php");
include("./misc/lib.php");
include("./variables.php");
include("./in_out.php");
include("./compute_particularity.php");


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

$connexion = connexion ($db_host, $db_user, $db_password,$db_name);


// Load all id of non-isomorphic graph with N vertices

$all_graphs_id_no_isomorphic_with_N_vertices = looking_for_graphs_no_isomophormic_with_N ($N,$connexion);
$lg = sizeof($all_graphs_id_no_isomorphic_with_N_vertices);
for($i=0;$i<$lg;$i++){
    $id_graph = $all_graphs_id_no_isomorphic_with_N_vertices[$i];
    unset($graph);unset($degree);unset($X_sup6);$card_X_sup6=0;unset($NoD);$nbNoD=0;
    mysql_graph_loading ($id_graph,$connexion);

    if ($nbD=="ok") {
        $type_B_bool = type_B ();
        if ($ddisplay=="ok")
             display_vecteur("Number of vertice x in Xsup6 with N(x) in Xinf6 (card_X_sup6=$card_X_sup6): ",$NoD,$nbNoD);
        if ($store_D=="ok")     {
            store_wheel_in_Xinf6($NoD,$nbNoD,$id_graph,$N,$connexion);
            if ($card_X_sup6>2) store_B_property($type_B_bool,$id_graph,$N,$connexion);else store_A_property($type_B_bool,$id_graph,$N,$connexion);
            }
        }

    if ($comput_T_1=="ok"){
        $T_1= can_realize_T_1 ();
        if ($tdisplay=="ok") {
            if ($T_1) print "<br>We could operate T_1 ."; else print "<br>We couldn't operate T_1 !!!!";
            }
        if ($store_T_1=="ok")   store_T_1_property($T_1,$id_graph,$N,$connexion);
        }

    if ($gdisplay=="ok") {
        display_graph ();
        display_vecteur("Number in Xsup6 : ",$X_sup6,$card_X_sup6);
        select_graph_not_T_1 ($connexion);
        }
    }
?>
