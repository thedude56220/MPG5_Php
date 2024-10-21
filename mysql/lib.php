<!-- DATE DE CREATION: 18/06/02 -->

<?

function connexion ($db_host, $db_user, $db_password,$db_name){
    $connexion = mysql_connect($db_host, $db_user, $db_password); 
    if (!$connexion) {
        echo "Impossible d'effectuer la connexion sur la base MySql";
        exit;
        }
    $db = mysql_select_db($db_name, $connexion);
    if (!$db) {
        echo "Impossible de sélectionner la base de données $db_name de MySql";
        exit;
        } 
    return($connexion);
    } 
    
    
function query_result ($query, $connexion) {
    $r_query = mysql_query($query,$connexion);
    $num_rows = mysql_num_rows($r_query);
    $num_fields = mysql_num_fields($r_query);
    for($i=0;$i<$num_rows;$i++)
        for ($j=0;$j<$num_fields;$j++)
            $result[$i][$j] = mysql_result($r_query,$i,$j);
    mysql_free_result($r_query);
    return ($result);
    }

function  mysql_insert_graph ($N,$connexion) {
    $t = microtime();
    $query  = "insert into graph values ('','$N','','$t')";
    $query2 = "select id_graph from graph where temps like '$t%'";
    $r_query = mysql_query($query,$connexion);
    $r_query2 = mysql_query($query2,$connexion);
    $id = mysql_result($r_query2,0,0);
    print "<br>id=$id  query2=$query2";
    return ($id);
}

function insert_mysql_edge ($id_graph,$N,$start,$end,$connexion){
    //print "<br>$id_graph,$start,$end,$connexion<br>";
    $query  = "insert into edge values ('','$id_graph','$N','$start','$end')";
    $r_query = mysql_query($query,$connexion);
}

function number_of_graph ($N,$connexion){
    $query  =  "select count(*) from graph where n_graph  = '$N'";
    $r_query = mysql_query($query,$connexion);
    $nb = mysql_result($r_query,0,0);
    return ($nb);
}    
        
?>
