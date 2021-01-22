<?php

    require_once __ROOT__.'\model\DAO\DAO.php';
    require_once __ROOT__.'\model\DAO\FamigliaDAO.php';

    require_once __ROOT__.'\model\VO\OrdineVO.php';
    require_once __ROOT__.'\model\VO\FamigliaVO.php';
    require_once __ROOT__.'\model\VO\GenereVO.php';

    class GenereDAO extends DAO{

        public function get($id){

            $result = $this->performCall(array($id), 'get_genere');

            if(isset($result['failure'])) return new GenereVO();

            $result['famigliaVO'] = (new FamigliaDAO())->get($result['famiglia']); unset($result['famiglia']);

            return new GenereVO(...$result);

        }

        public function getAll(){

            $VOArray = array();

            $result = $this->performMultiCAll(array(), 'get_all_genere');
            if( isset($result['success']) && !$result['success']) return $VOArray;


            foreach ($result as $element){

                $element['famigliaVO'] = new FamigliaVO($element['famiglia'], $element['nomeScientifico_famiglia'],
                    new OrdineVO($element['ordine'],  $element['nomeScientifico_ordine']));

                /** Scartiamo gli attributi non più necessari.*/
                unset($element['famiglia'],  $element['nomeScientifico_famiglia'],
                    $element['ordine'], $element['nomeScientifico_ordine']);

                $VOArray [] = new GenereVO(...$element);

            }

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
                    return isset($result['failure']);

                } else {

                    $result = $this->performCall($element->smartDump(true), 'create_famiglia');

                    /** Se la query non è stata un fallimento.*/
                    if(!isset($result['failure']))
                        $element = new $element( ...$result, ...$element->varDumps(true));

                    return !$element->getId() === null;

                }
            }

            $element->setFamigliaVO(new FamigliaVO());
            return false;

        }

        public function delete(VO &$element) : bool{
            return $this->defaultDelete($element, 'delete_genere');
        }
    }