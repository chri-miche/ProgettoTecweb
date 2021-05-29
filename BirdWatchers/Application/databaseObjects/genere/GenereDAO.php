<?php

require_once __DIR__ . "/../DAO.php";
require_once __DIR__ . "/../famiglia/FamigliaDAO.php";

require_once __DIR__ . "/../ordine/OrdineVO.php";
require_once __DIR__ . "/../famiglia/FamigliaVO.php";

    class GenereDAO extends DAO{

        public function get($id){

            $result = $this->performCall(array($id), 'get_genere');

            if(isset($result['failure'])) return new GenereVO();

            $result['famigliaVO'] = (new FamigliaDAO())->get($result['famiglia']); unset($result['famiglia']);
            print_r($result);
            return new GenereVO(...array_values($result));

        }

        public function getAll(){

            $VOArray = array();

            $result = $this->performMultiCAll(array(), 'get_all_genere');
            if( isset($result['failure'])) return $VOArray;


            foreach ($result as $element){

                $element['famigliaVO'] = new FamigliaVO($element['famiglia'], $element['nome_scientifico_famiglia'],
                    new OrdineVO($element['ordine'],  $element['nome_scientifico_ordine']));

                /** Scartiamo gli attributi non più necessari.*/
                unset($element['famiglia'],  $element['nome_scientifico_famiglia'],
                    $element['ordine'], $element['nome_scientifico_ordine']);

                $VOArray [] = new GenereVO(...array_values($element));

            }

            return $VOArray;

        }

        public function getAllFilterBy(?int $famiglia = null, ?int $ordine = null) : array {

            $VOArray = array();

            if(!is_null($famiglia)){

                $famigliaVO = (new FamigliaDAO())->get($famiglia);

                /** Questa chiamata da per scontato tu conosca la famiglia quindi non la va a prendere.*/
                $result = $this->performMultiCAll(array($famiglia), 'get_all_genere_filter_by_famiglia');
                if(isset($result['failure'])) return $VOArray;

                foreach ($result as $element)
                    $VOArray []= new GenereVO(...array_values($element), ...[$famigliaVO]);

            } else if(isset($ordine)){

                $ordineVO = (new OrdineDAO())->get($ordine);

                $result = $this->performMultiCAll(array($ordine), 'get_all_genere_filter_by_ordine');
                if(isset($result['failure'])) return $VOArray;

                foreach ($result as $element) {

                    /** Si crea per ognuno la sua famiglia.*/
                    $famigliaVO = new FamigliaVO($element['f_id'], $element['f_nome_scientifico'], $ordineVO);
                    unset($element['f_id'], $element['f_nome_scientifico']);

                    $VOArray []= new GenereVO(...array_values($element), ...[$famigliaVO]);
                }
            } else
                $VOArray = $this->getAll();

            return $VOArray;

        }

        /** @param GenereVO $element */
        public function checkId(VO &$element) : bool{
            return $this->idValid($element, 'genere_id');
        }

        /** @param GenereVO $element : Elemento famiglia da salvare. */
        public function save(VO &$element) : bool{

            if(is_null($element->getFamigliaVO()->getId())) return false;

            $famigliaDAO = new FamigliaDAO();
            $famigliaVO = $element->getFamigliaVO();

            /** Se esiset la famiglia alla quale ci riferiamo. */
            if($famigliaDAO->checkId($famigliaVO)){

                if($this->checkId($element)){

                    $result = $this->performCall($element->smartDump(), 'update_genere');
                    return !isset($result['failure']);

                } else {

                    $result = $this->performCall($element->smartDump(true), 'create_genere');

                    /** Se la query non è stata un fallimento.*/
                    if(!isset($result['failure']))
                        $element = new $element( ...array_values($result), ...$element->varDumps(true));

                    return !is_null($element->getId());

                }
            }

            $element->setFamigliaVO(new FamigliaVO());
            return false;

        }

        public function delete(VO &$element) : bool{
            return $this->defaultDelete($element, 'delete_genere');
        }
    }