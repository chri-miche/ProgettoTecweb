<?php
require_once __DIR__ . "/../DAO.php";

require_once __DIR__ . "/../specie/SpecieVO.php";
require_once __DIR__ . "/../conservazione/ConservazioneDAO.php";
require_once __DIR__ . "/../genere/GenereDAO.php";

class SpecieDAO extends DAO {

    public function get($id) {

        $result = $this->performCall(array($id), 'get_specie');

        /** Se fallisce la ricerca ritorniamo un elemento vuoto.*/
        if(isset($result['failure'])) return new GenereVO();

        $result['genereVO'] = (new GenereDAO())->get($result['genere']); unset($result['genere']);
        $result['conservazioneVO'] = (new ConservazioneDAO())->get($result['conservazione']); unset($result['conservazione']);

        return new SpecieVO(...array_values($result));
    }

    public function getAll() {

        $VOArray = array();

        $result = $this->performMultiCAll(array(), 'get_all_specie');
        if(isset($result['failure'])) return $VOArray;


        foreach ($result as $element){
                /** Creazione di un genere. Che a sua volta crea la conservazione e sua volta l ordine.*/
                $element['genereVO'] = new GenereVO( $element['genere'], $element['nome_scientifico_genere'],
                    new FamigliaVO($element['famiglia'], $element['nome_scientifico_famiglia'],
                        new OrdineVO($element['ordine'],  $element['nome_scientifico_ordine'])));

                /** Creazione di stato estinzione.*/
                $element['conservazioneVO'] = new ConservazioneVO(
                    $element['conservazione'], $element['nome'],
                    $element['prob_estinzione'], $element['descrizione']);

                /** Scartiamo gli attributi non più necessari.*/
                unset($element['genere'], $element['nome_scientifico_genere'],
                    $element['conservazione'],  $element['nome_scientifico_famiglia'],
                    $element['ordine'], $element['nome_scientifico_ordine'], $element['famiglia']);

                unset($element['conservazione'],$element['nome'],
                    $element['prob_estinzione']);

                $VOArray [] = new SpecieVO(...array_values($element));
        }
        return $VOArray;
    }

    public function getAllFilterBy(?int $genere = null, ?int $famiglia = null, ?int $ordine = null): array{

        $VOArray = array();

        if(!is_null($genere)){

            $genereVO = (new GenereDAO())->get($genere);

            /** Ci basta prendere il minimo indispensabile dove id genere uguale.*/
            $result = $this->performMultiCAll(array($genereVO->getId()), 'get_all_filter_specie_by_genere');
            if(isset($result['failure'])) return $VOArray;

            foreach ($result as $specie) {

                $conservazioneVO = (new ConservazioneDAO())->get($specie['c_codice']);
                unset($specie['c_codice']);

                $VOArray [] = new SpecieVO(...array_values($specie), ...[$genereVO, $conservazioneVO]);

            }

        } else if(!is_null($famiglia)){

            $famigliaVO = (new FamigliaDAO())->get($famiglia);
            /** Dobbiamo fare la join con genere e prendere sia genere che specie per ognuno.*/

            $result = $this->performMultiCAll(array($famigliaVO->getId()), 'get_all_filter_specie_by_famiglia');
            if(isset($result['failure'])) return $VOArray;


            foreach ($result as $specie) {

                $genereVO = new GenereVO($specie['g_id'], $specie['g_nomeScientifico'], $famigliaVO);
                $conservazioneVO = (new ConservazioneDAO())->get($specie['c_codice']);

                unset($specie['g_id'], $specie['g_nomeScientifico']);
                unset($specie['c_codice']);


                $VOArray [] = new SpecieVO(...array_values($specie), ...[$genereVO, $conservazioneVO]);

            }

        } else if(!is_null($ordine)){
            $ordineVO = (new OrdineDAO())->get($ordine);
            /** Dobbiamo fare un sacco di join. */

            $result = $this->performMultiCAll(array($ordineVO->getId()), 'get_all_filter_specie_by_ordine');
            if(isset($result['failure'])) return $VOArray;

            foreach ($result as $specie){

                $famigliaVO = new FamigliaVO($specie['f_id'], $specie['f_nomeScientifico'], $ordineVO);
                unset($specie['o_id'], $specie['o_nomeScientifico']);

                $genereVO = new GenereVO($specie['g_id'], $specie['g_nomeScientifico'], $famigliaVO);

                $conservazioneVO = (new ConservazioneDAO())->get($specie['c_codice']);


                unset($specie['g_id'], $specie['g_nomeScientifico'], $specie['f_id'], $specie['f_nomeScientifico']);
                unset($specie['c_codice']);

                $VOArray [] = new SpecieVO(...array_values($specie), ...[$genereVO, $conservazioneVO]);
            }

        } else {
            /** Nessuna selezionata ma abbiamo filtro su quantitò.*/
            /** tutti limitati.*/
            return $this->getAll();

        }

        return $VOArray;
    }

    public function search(string $text) : array {

        $VOArray = array();

        $result = $this->performMultiCAll(array($text), 'search_all_specie');
        if(isset($result['failure'])) return $VOArray;

        foreach ($result as $element){

            /** Creazione di un genere. Che a sua volta crea la conservazione e sua volta l ordine.*/
            $element['genereVO'] = new GenereVO( $element['genere'], $element['nome_scientifico_genere'],
                new FamigliaVO($element['conservazione'], $element['nome_scientifico_famiglia'],
                    new OrdineVO($element['ordine'],  $element['nome_scientifico_ordine'])));

            /** Creazione di stato estinzione.*/
            $element['conservazioneVO'] = new
                ConservazioneVO($element['conservazione'], $element['nome'], $element['prob_estinzione'], $element['descrizione']);

            /** Scartiamo gli attributi non più necessari.*/
            unset($element['genere'], $element['nome_scientifico_genere'], $element['conservazione'],
                $element['nome_scientifico_famiglia'],  $element['ordine'], $element['nome_scientifico_ordine']);

            unset($element['conservazione'],$element['nome'], $element['prob_estinzione'], $element['descrizione']);

            $VOArray [] = new SpecieVO(...array_values($element));

        }

        return $VOArray;

    }


    /** @param SpecieVO $element */
    public function checkId(VO &$element) : bool {
        return $this->idValid($element, 'specie_id');
    }

    /** @param SpecieVO $element */
    public function save(VO &$element) : bool {

        /** Non è possibile salvare qualcosa che non ha un ordine o che non ha associato un grado di estinzione.*/

        $parentVO = $element->getGenereVO();

        if(is_null($parentVO->getId()) || is_null($element->getConservazioneVO()->getId())) return false;
        $parentDAO = new GenereDAO();

        /** Se effettivamente esiste questo ordine a cui si fa rifermento.*/
        if($parentDAO->checkId($parentVO)) {
            /** Se esiste l id della conservazione, quella va semplicemente aggiornata.*/
            if ($this->checkId($element)) {

                $result = $this->performNoOutputModifyCall($element->smartDump(), 'update_specie');
                return isset($result['failure']);

            } else {

                $result = $this->performCall($element->smartDump(true), 'create_specie');

                if(!isset($result['failure']))
                    $element = new $element(...array_values($result), ...$element->varDumps(true));

                return !$element->getId() === null;

            }
        }

        $element->setGenereVO(new GenereVO());
        return false;

    }

    public function delete(VO &$element) : bool {
        return $this->defaultDelete($element, 'delete_specie');
    }
}