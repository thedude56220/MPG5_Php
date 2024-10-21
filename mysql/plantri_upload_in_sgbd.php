<?
include("./configuration.php");
include("./lib.php");
include("../variables.php");
include("../in_out.php");



/******************************************************************/
//               MAIN IN
/******************************************************************/

if ($url==""){
    print "<br>Error url=$url<br>";
    exit();
    }
if ($login !="")    $db_user=$login;
if ($ip !="")       $db_host=$ip;
if ($passwd !="")   $db_password=$passwd;
if ($base !="")     $db_name=$base;


/******************************************************************/
//               MAIN OUT
/******************************************************************/

$connexion = connexion ($db_host, $db_user, $db_password,$db_name);

$array = load_plantri_ascii_graphs($url);  
$nb_graphs = sizeof($array);
for ($i=0; $i<$nb_graphs; $i++){
    $row = explode(" ", $array[$i]);
    $N = $row[0];
    if ($N>0){
        $one_neigbhorhood  = explode(",", $row[1]);
        $id_graph = mysql_insert_graph ($N,$connexion);
        for ($j=0;$j<=$N;$j++){
            $degree_of_j = strlen($one_neigbhorhood[$j]);
            $start=$j+1;
            for ($k=0;$k<$degree_of_j;$k++){
                $ascii_pos = ord($one_neigbhorhood[$j][$k]);
                $vertex = $ascii_pos - $OFFSET_ASCII ;
                // CONVENTION : Vertices start with 1 and not 0
                $end=$vertex;
                insert_mysql_edge ($id_graph,$N,$start,$end,$connexion);
                }
            }
        }
    }   
        

?>
