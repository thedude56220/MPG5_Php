<?
/******************************************************************/

//              INIT GRAPH

/******************************************************************/


function init_graph ($starting_graph){
    global $N, $degree,$graph,$X_sup6,$card_X_sup6,$card_X_inf6;

    // RESET ALL global variables
    $N=14;
    $X_sup6 [1]=1; $X_sup6 [2]=8;//=array(1,8);
    $card_X_sup6=2;$degree[1]=6;$degree[8]=6;
    $X_inf6 =array(2,3,4,5,6,7,9,10,11,12,13,14);
    $card_X_inf6=12;
    $degree[2]=5;$degree[3]=5;$degree[4]=5;$degree[5]=5;
    $degree[6]=5;$degree[7]=5;$degree[9]=5;$degree[10]=5;
    $degree[11]=5;$degree[12]=5;$degree[13]=5;$degree[14]=5;
    $NoD="";$nbNoD=0;

    // INIT Graph on MPG5_14
    for ($i=1;$i<=$N;$i++){
        $dg=$degree[$i];
        for($j=1;$j<=$degree[$i];$j++)
            $graph[$i][$j]=$starting_graph[$i][$j];
        }
    return $graph;
    }

/******************************************************************/

//              PLANTRI ASCII GRAPHS

/******************************************************************/


function load_plantri_ascii_graphs($url){

    $fp = fopen($url,"r");
     if (!$fp) {
        print "<br>File system read access not authorized for $url.";
        exit();
        }
    if($fp != 0) while (!feof($fp)) $buffer .= fread($fp, 3012);

    fclose($fp);
    $array = explode("\n", $buffer);
    return ($array);
}

/******************************************************************/

//              MYSQL graph loading

/******************************************************************/


function mysql_graph_loading ($id,$connexion){
    global $graph, $degree, $X_sup6,$card_X_sup6,$card_X_inf6;
    $query  = "select * from edge where  id_graph   = '$id' order by start";
    $r_query = mysql_query($query,$connexion);
    $num_rows = mysql_num_rows($r_query);
    //unset ($graph);
    //unset ($degree);
    for($i=0;$i<$num_rows;$i++){
        $start =  mysql_result($r_query,$i,3);
        $end =  mysql_result($r_query,$i,4);
        $degree_before =$degree [$start];
        $degree [$start]++; // each neighbord start with index 1 in graph array
        $graph [$start] [$degree [$start]] = $end;
        if (($degree_before < 6) && ($degree [$start]>5)){
            $card_X_sup6++;
            $X_sup6[$card_X_sup6]=$start;
            }
        }
}

function looking_for_graphs_no_isomophormic_with_N ($N,$connexion){
    $query  = "select id_graph from graph where  n  = '$N'";
    $r_query = mysql_query($query,$connexion);
    $num_rows = mysql_num_rows($r_query);
    //unset($all_graphs_id_no_isomorphic_with_N_vertices);
    for($i=0;$i<$num_rows;$i++)
        $all_graphs_id_no_isomorphic_with_N_vertices[$i]= mysql_result($r_query,$i,0);
    return ($all_graphs_id_no_isomorphic_with_N_vertices);
    }

function store_wheel_in_Xinf6($NoD,$nbNoD,$id_graph,$N,$connexion){
    for($i=1;$i<=$nbNoD;$i++){
        $v =$NoD[$i];
        $query  = "insert into wheel_xinf6 values ('','$id_graph','$N','$v')";
        $r_query = mysql_query($query,$connexion);
        }
    }

function store_T_1_property($T_1,$id_graph,$N,$connexion){
        if ($T_1) $bool="T^{-1} possible"; else $bool="T^{-1} not possible";
        $query  = "insert into T_1_properties values ('','$id_graph','$N','$T_1','$bool')";
        $r_query = mysql_query($query,$connexion);
}

function store_B_property($type_B_bool,$id_graph,$N,$connexion){
        if ($type_B_bool) $bool="Graph B"; else $bool="Not Graph B";
        $query  = "insert into B_properties values ('','$id_graph','$N','$type_B_bool','$bool')";
        $r_query = mysql_query($query,$connexion);
}

function store_A_property($type_B_bool,$id_graph,$N,$connexion){
        $bool="Graph A";
        $query  = "insert into B_properties values ('','$id_graph','$N','$type_B_bool','$bool')";
        $r_query = mysql_query($query,$connexion);
}

/******************************************************************/

//              DIMACS GRAPH

/******************************************************************/



function load_dimacs_file ($url){

global $graph, $degree, $N, $error;

if ($url==""){
    print "<br> Function load_dimacs_file : url is empty. Please send a good Dimacs file place.<br>";
    return;
    }

  $content_array = file($url);
  $nb_ligne = sizeof ($content_array);
  $i=0;
  while (($i<=$nb_ligne)&&($content_array[$i][0]!="p")) $i++;
  $N = $content_array[$i][2];
  for ($j=1;$j<=$N;$j++) $degree[$j]=0;
  for ($j=$i;$j<=$nb_ligne;$j++){
    if ($content_array[$i][0]=="e") {
        $degree [$content_array[$i][1]]++; // each neighbord start with index 1 in graph array
        $graph [$content_array[$i][1]] [$degree [$content_array[$i][1]]] = $content_array[$i][2];
        }
    }

}


function dimacs_url ($url,$item){
global $graph, $degree, $N, $error;


if ($url==""){
    //print "<br> Function dimacs_url : url is empty. Please send a good Dimacs file place.<br>";
    return;
    }
 verification ();
 $url=$url."_".$item.".txt";


 if ($error<0)  {
    print "<br>Error problem<br> No DIMACS graph created.<br>";
    exit();
    }
 $fp = fopen($url, "w");
 if (!$fp) {
    print "<br>File system write access not authorized for $url.";
    exit();
    }
 fwrite($fp, "c FILE: MPG5 generator\n");
 fwrite($fp, "c SOURCE: Philippe Rolland-Balzon (prolland@sepro-robotique.com)\n");
 fwrite($fp, "c DESCRIPTION: Graph based on explosion and diagonal operations. \n");
 fwrite($fp, "c              Maximal Planar Graph with minimum degree five. \n");
 fwrite($fp, "p edge $N ? \n");
 for($i=1;$i<=$N;$i++){
    for($j=1;$j<=$degree[$i];$j++){
        $val=$graph[$i][$j];
        fwrite($fp, "e $i $val\n");
        }
    }
 fclose($fp);
 if ($verbose=="ok") print "<br>Writing MPG5 Dimacs Format in $url.";
}



function dimacs_display (){
global $graph, $degree, $N, $error;




 verification ();
 if ($error<0)  {
    print "<br>Error problem<br> No DIMACS graph created.<br>";
    exit();
    }


 print "c FILE: MPG5 generator<br>";
 print "c SOURCE: Philippe Rolland-Balzon (prolland@sepro-robotique.com)<br>";
 print "c DESCRIPTION: Graph based on explosion and diagonal operations. <br>";
 print "c              Maximal Planar Graph with minimum degree five. <br>";
 print "p edge $N ? <br>";
 for($i=1;$i<=$N;$i++){
    for($j=1;$j<=$degree[$i];$j++){
        $val=$graph[$i][$j];
        print "e $i $val<br>";
        }
    }
}



/******************************************************************/

//              DISPLAY

/******************************************************************/



function display_graph (){
global $graph, $degree, $N;
print "\n<br><br>Graph Display(N=$N)<br>vertex(degree) : list of neighbords in clockwise order<br>\n";
for($i=1;$i<=$N;$i++){
    print "\n<br>$i ($degree[$i]) : ";
    for ($j=1;$j<=$degree[$i];$j++){
        $n=$graph[$i][$j];
        print " $n ";
        }
    }
print"\n<br>";
}



function display_vecteur($title,$vect,$lg){
global $degree;
print "\n<br>$title: (lg=$lg)<br>\n";
//if ($lg<=0)  $lg = sizeof($vect);
for($i=1;$i<=$lg;$i++){
    $dg=$degree[$vect[$i]];
    print "\n value[$i]=$vect[$i] dg=$dg<br>";
    }
print"\n<br>";
}



function time_delta ($delta){
print "\n<br>Time:$delta(s)<br>\n";
}



function display_all_flip(){
global $N,$nbflip;
print "<br> Display Number of Flip by Order";
for($i=14;$i<=$N;$i++) {
    if ($nbflip[$i] != "") print "<br> N=$i number of flips $nbflip[$i].";
    }
print"\n<br>";

}



?>
