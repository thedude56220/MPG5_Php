<?
/******************************************************************/
//              EXPLOSION
//              Rolland Balzon Philippe V1.4
// Last modification : 7/6/2002
/******************************************************************/


//----------------------------------------------------------//
// Nv : first neighborhood of current vertex v to explose   //
// Nv1 : first neighborhood of v after explosion            //
// Nv2 : first neighborhood of N+1 after explosion          //
//----------------------------------------------------------//
$Nv[]="";$Nv1="";$Nv2[]="";

/* Graphically :
    v1
N       v
    v2
*/

/******************************************************************/
//              EXPLOSION T VERTICES   SELECTION
/******************************************************************/

function vertex_selection (){
    global $X_sup6, $card_X_sup6, $N, $degree,$v, $verbose;

    $pos=rand (1,$card_X_sup6);
    $v= $X_sup6[$pos];
    if ($verbose=="ok") print "<br>vertex_selection::: card_X_sup6=$card_X_sup6 pos=$pos X_sup6[$pos]=$X_sup6[$pos] v=$v";
    }

/*****************************/
function vertex_selection_v1 ($v){
    global $X_sup6, $card_X_sup6, $graph, $degree;
    $pos=rand (1,$degree[$v]-3);
    return ($pos);
    }

/*****************************/
function vertex_selection_v2($v,$pos1){
    global $X_sup6, $card_X_sup6, $graph, $degree;

    $start=$pos1+3;$end=(($pos1-3)%($degree[$v]))+$degree[$v];
    if ($end>$degree[$v]) $end=$degree[$v];
    if ($start<$end) $pos=rand ($pos1+3,$end);else  $pos=$start;
    return ($pos);
    }

/******************************************************************/
//               EXPLOSION T NEIGHBORHOOD DISPATCHING
/******************************************************************/

function cp_neighborhood($v){
    global $graph, $degree;
    for($i=1;$i<=$degree[$v];$i++)
        $Nv[$i]=$graph[$v][$i];
    return $Nv;
    }

/************************************/
// about Nv and Nn+1
/************************************/

function maj_Nv_and_Nn ($v,$pos1,$pos2){
    global $graph, $degree, $N, $Nv1, $Nv2;

    $Nv = cp_neighborhood($v);
    for($i=0;$i<=$N+1;$i++){$Nv1[$i]="";$Nv2[$i]="";} // INIT
    $j1=1;for($i=$pos1;$i<=$pos2;$i++) {$Nv1[$j1]=$Nv[$i];$j1++;}
    $j2=1;for($i=$pos2;$i<=$degree[$v];$i++) {$Nv2[$j2]=$Nv[$i];$j2++;}
    for($i=1;$i<=$pos1;$i++) {$Nv2[$j2]=$Nv[$i];$j2++;}

    $Nv1[$j1]=$N+1;
    $Nv2[$j2]=$v;
    }

function maj_vertex($v,$Nvi){
    global $graph,$degree,$N, $verbose;

    $i=1;
    if ($verbose=="ok") print "<br>BEGIN maj_vertex($v)<br>\n";
    while($Nvi[$i]!=""){
        $graph[$v][$i]=$Nvi[$i];
        if ($verbose=="ok"){print " graph[$v][$i]=$Nvi[$i] ";}
        $i++;
        }
    $degree[$v]=$i-1;
    // matrix cleaning
    for($i=$degree[$v]+1;$i<=$N;$i++){
        $graph[$v][$i]="";
        if ($verbose=="ok"){$tmp=$graph[$v][$i];print " cleaning-graph[$v][$i]=$tmp \n";}
        }
    if ($verbose=="ok") print "<br>END update_vertex($v)<br>\n";
    }

function maj_neighborhood(){
    global $graph, $degree, $Nv2, $N, $v, $v1, $v2, $verbose;

    $dg=$degree[$N+1];
    $i=1;
    while ($Nv2[$i]!=""){
        $j=$Nv2[$i];
        if (($j!=$v1) && ($j!=$v2)){
            for($k=1;$k<=$degree[$j];$k++){
                $vois = $graph[$j][$k];
                if ($graph[$j][$k]==$v){
                    $graph[$j][$k]=$N+1;$k=$degree[$j]+2;
                    if ($verbose=="ok") print " ECHANGE ... autour de $j du sommet $v en $N+1 ";
                    }
                }
            }
        $i++;
        }
    }

function maj_graph($v){
    global $graph, $Nv1,$Nv2, $N;

    maj_vertex($v,$Nv1);
    maj_vertex($N+1,$Nv2);
    maj_neighborhood();
    }

/************************************/
// about Nv1 and Nv2
/************************************/


function local_update_v1_v2 ($v,$v1_or_v2,$its_v1){
    global $degree,$graph,$N;

    $t=1;
    for($j=1;$j<=$degree[$v1_or_v2];$j++){
        $Na[$t]=$graph[$v1_or_v2][$j];
        if (($Na[$t]==$v)&&($its_v1=="yes"))
            {$t++;$Na[$t]=$N+1;}
        if (($Na[$t]==$v)&&($its_v1=="no"))
            {$Na[$t]=$N+1;$t++;$Na[$t]=$v;}
        $t++;
        }
    $degree[$v1_or_v2]++;
    for($j=1;$j<=$degree[$v1_or_v2];$j++) $graph[$v1_or_v2][$j]=$Na[$j];
}


function explosion (){
    global $pos1,$pos2,$v,$v1,$v2,$graph,$N, $selected;
    vertex_selection();

    $pos1 = vertex_selection_v1($v);
    $pos2 = vertex_selection_v2 ($v,$pos1);

    $v1=$graph[$v][$pos1];
    $v2=$graph[$v][$pos2];
    $old_dg_v1=$degree[$v1];$old_dg_v2=$degree[$v2];

    if ($selected == "ok") print "<br> Selected N=$N (before) vertices v=$v dg($v)=$degree[$v]: neighbors v1=$v1(pos=$pos1) v2=$v2(pos=$pos2)<br>";

    maj_Nv_and_Nn($v,$pos1,$pos2);
    maj_graph($v);

    local_update_v1_v2($v,$v1,"yes"); // We looking for $v around $v1; and place a new neighborhord $N after
    local_update_v1_v2($v,$v2,"no"); // We looking for $v around $v2; and replace $v by new neighborhord $N and place $v after



    Xsup6_add_vertex($v1,$old_dg_v1);Xsup6_add_vertex($v2,$old_dg_v2);Xsup6_add_vertex($N,0);
    Xsup6_del_vertex($v);
    }

?>
