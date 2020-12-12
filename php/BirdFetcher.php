
<?php


    /** Roba per fare prove. */
    require_once "DBConnector.php";
    $dbAccess = new DBAccess();

    $connectionOpen = $dbAccess->openConnection();

    if($connectionOpen){

        $listaOrdini = $dbAccess->getOrdineList();
        $dbAccess->closeConnection();

        if($listaOrdini){
            $defList = '<dl id ="ordine">';

            foreach($listaOrdini as $ordine){
                $defList .= '<dt>' .$ordine['nomeScientifico']  . "Conosciuto anche come:" .$ordine['nome'] .'<dt>';
                $defList .= '<dd>';

                $defList .= '<button>' .$ordine['nomeScientifico'] . '</button>';
            }
        }

        echo $defList;

    } else {

        // Errore.

    }
