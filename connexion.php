<?

       // selection de la BD fournisseur pour verifier le code
        $db = db_select("mpg5", $connexion);
        if (!$db) {
            echo "Impossible de sélectionner cette base de données";
            exit;
            }
         $query = "select * from Fournisseurs where FOU_CODE ='$MANUAL_FOU_CODE'";
         $resultat_sql = mysql_query($query,$connexion);
         $nombreligne = mysql_num_rows($resultat_sql);
         if ((  $nombreligne != "1" ) || ($MANUAL_FOU_CODE =="") || ($MANUAL_FOU_CODE =="0"))
            {
            print "Numero de Code Fournisseur <u>$MANUAL_FOU_CODE</u> incorrect !";
            ?> </font>


?>
