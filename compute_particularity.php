<? 

/******************************************************************/ 
//  compute $X_NotD$ number of vertice x in Xsup6 with N(x) in Xinf6 
/******************************************************************/

function all_neighborhood_in_Xinf6($y){ 
    global $degree,$graph;         
    for($i=1;$i<=$degree[$y];$i++) 
        if ($degree[$graph[$y][$i]]>5) return (FALSE);     
    return (TRUE); 
}


function type_B (){  
    global $X_sup6,$card_X_sup6,$nbNoD,$NoD;      
    $nbNoD=0;     
    for($i=1;$i<=$card_X_sup6;$i++){       
        if (all_neighborhood_in_Xinf6($X_sup6[$i])==TRUE) {             
            $nbNoD++;             
            $NoD[$nbNoD]=$X_sup6[$i];      
            }         
        } 
    if ($nbNoD==$card_X_sup6) 
        return (TRUE); 
    else 
        return(FALSE);
    } 

//______________________________________________________ 
// for all vertices not in $NoD, there exists at least 
// a neighbord in Xsup6, ie at least a vertex to apply D 
//______________________________________________________

function looking_for_egde_eD_with_max_X_NotD_in_D_eD(){                                   
global $N,$c,$d, $nbNoD, $graph, $degree, $edge;                                                                                                                                     
$max_nbNoD=0;     
//print "<br> max_nbNoD=$max_nbNoD <br>";                                                  
for ($i=1;$i<=$N;$i++){        
    if (($degree[$i]>5)&&(all_neighborhood_in_Xinf6($i)==FALSE)){                                
        for ($j=1;$j<=$degree[$i];$j++){                                                             
            $a=$i;$b=$graph[$i][$j];                                                                 
            if ($degree[$b]>5){                                                                          
                //print "<br>a=$a($degree[$a]) b=$b($degree[$b])<br>";                                     
                flip2($a,$b);                                                                            
                type_B ();                                                                               
                if ($max_nbNoD<=$nbNoD) {                                                                     
                    $max_nbNo=$nbNoD;                                                                        
                    $edge[1][1]=$a;$edge[1][2]=$b;$edge[1][3]=$nbNoD;
                    }                                   
                flip2($c,$d);// restore                                                                  
                }                                                                                    
            }                                                                                    
        }                                                                                    
    }                                                                                
}                                                                                        

                                                                          

function looking_for_egde_eD_with_min_X_NotD_in_D_eD(){                           
 global $N,$c,$d, $nbNoD, $graph, $degree, $edge;                                 
                                                                                  
    $min_nbNoD=$N*$N; 
    $min_nbNoD=0;                                                            
    //print "<br> min_nbNoD=$min_nbNoD <br>";                                       
    for ($i=1;$i<=$N;$i++){                                                       
        if (($degree[$i]>5)&&(all_neighborhood_in_Xinf6($i)==FALSE)){             
            for ($j=1;$j<=$degree[$i];$j++){                                      
                $a=$i;$b=$graph[$i][$j];                                          
                if ($degree[$b]>5){                                               
                    //print "<br>a=$a($degree[$a]) b=$b($degree[$b]) maxNbD=$min_nbNoD<br>";          
                    flip2($a,$b);                                                 
                    type_B ();                                                    
                    if ($min_nbNoD<=$nbNoD) {                                      
                        $min_nbNo=$nbNoD;                                         
                        $edge[1][1]=$a;$edge[1][2]=$b;$edge[1][3]=$nbNoD;
                        }        
                    flip2($c,$d);// restore                                       
                    }                                                             
                }                                                               
            }                                                                     
        }                                                                        
}                                                                                 

/******************************************************************/ 
//  compute $X_T_1$ number of 4-uplet [x,y;z,t] to operate T-1
/******************************************************************/

function looking_for_x_1 ($x_k,$x_prime, $x_second){
    global $graph,$degree,$card_X_sup6;
    // around $x_second in clockwise order we looking for sequence ...,x_k,x_prime, x_1.
    for($i=1;$i<=$degree[$x_second];$i++)
        if ($graph[$x_second][$i]==$x_prime){
            $x_1 = $graph[$x_second][$i+1];
            if ($i==$degree[$x_second]) $x_1 = $graph[$x_second][1];
            if (($degree[$x_1]>5)&&(!neighbord($x_1,x_k))){
                //print "<br> x_k=$x_k x_prime=$x_prime x_second=$x_second x_1=$x_1";
                return ($x_1);
                }
            }
    return(-1);
}

function looking_for_around ($x_k){
    global $graph,$degree,$card_X_sup6;
    for($i=1;$i<=$degree[$x_k];$i++){
        $x_prime=$graph[$x_k][$i];
        $x_second=$graph[$x_k][$i+1];
        if ($i==$degree[$x_k])$x_second=$graph[$x_k][1];
        $x_1 = looking_for_x_1($x_k,$x_prime, $x_second);
        if ($x_1>0) return (TRUE);
        }
    return (FALSE);
}

function can_realize_T_1 (){  
    global $graph,$degree,$X_sup6,$card_X_sup6;
    
    for($i=1;$i<=$card_X_sup6;$i++){
        $x_k=$X_sup6[$i];
        if (looking_for_around ($x_k)== TRUE) return (TRUE);
        }
    return (FALSE);
    }
        
function select_graph_not_T_1 ($connexion) {       
    $query = "select id_graph, N from t_1_properties where T_1_possible = '0'";
    $r_query = mysql_query($query,$connexion);
    $num_rows = mysql_num_rows($r_query);
    for($i=0;$i<$num_rows;$i++){
        $id_graph = mysql_result($r_query,$i,0);
        $N = mysql_result($r_query,$i,1);
        print "<br> select_graph_not_T_1 id_graph=$id_graph N=$N";
        }
    }
        
?>
