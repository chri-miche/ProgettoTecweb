<?php

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "VO" . DIRECTORY_SEPARATOR . "VO.php";

    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "DAO" . DIRECTORY_SEPARATOR . "ConservazioneDAO.php";
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "VO" . DIRECTORY_SEPARATOR . "GenereVO.php";

    class SpecieVO implements VO {


        /** Dati di uccello.*/

        /** @var int: Identificatroe dell uccello.*/
        private $id;

        private $nome_scientifico;
        private $nome_comune;

        /** Dati supplementari di uccelli.*/
        private $peso_medio;
        private $altezza_media;

        /** Dati visivi e rappresentativi.*/
        private $descrizione;
        private $immagine;


        /** Genere VO.*/
        private $genereVO; // Esso contiene un famiglia DAO che a sua volta contiene un ordine DAO.
        /** Conservazione VO. */
        private $conservazioneVO;

        /**
         * SpecieVO constructor.
         * @param int|null $id
         * @param string|null $nome_scientifico
         * @param string|null $nome_comune
         * @param int|null $peso_medio
         * @param int|null $altezza_media
         * @param string|null $descrizione
         * @param string|null $immagine
         * @param GenereVO|null $genereVO
         * @param ConservazioneVO|null $conservazioneVO
         */
        public function __construct(int $id = null, string $nome_scientifico = null, string $nome_comune = null,
                                    int $peso_medio = null, int $altezza_media = null, string $descrizione = '', string $immagine = '',
                                    GenereVO $genereVO = null, ConservazioneVO $conservazioneVO = null) {

            $this->id = $id;
            $this->nome_scientifico = $nome_scientifico;
            $this->nome_comune = $nome_comune;
            $this->peso_medio = $peso_medio;
            $this->altezza_media = $altezza_media;
            $this->descrizione = $descrizione;
            $this->immagine = $immagine;

            $this->genereVO = $genereVO ?? new GenereVO();

            /** Di default viene settato lo stato a essere non in estinzione. **/
            $this->conservazioneVO = $conservazioneVO ?? (new ConservazioneDAO)->getDefault();

        }

        public function __get($name){ return $this->$name ?? null; }

        public function varDumps(bool $id = false) : array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);

            return array_values($array);

        }

        public function smartDump(bool $id = false): array{

            $return = get_object_vars($this);

            if($id) unset($return['id']);

            $return['genereVO'] = $this->genereVO->getId();
            $return['conservazioneVO'] = $this->conservazioneVO->getId();

            return array_values($return);
        }

        /**
         * @return int */
        public function getId(): ?int{
            return $this->id;
        }

        /**
         * @param int $id */
        public function setId(int $id): void{
            $this->id = $id;
        }

        /**
         * @return string|null */
        public function getNomeScientifico(): ?string{
            return $this->nome_scientifico;
        }

        /**
         * @param string|null $nome_scientifico */
        public function setNomeScientifico(?string $nome_scientifico): void{
            $this->nome_scientifico = $nome_scientifico;
        }

        /**
         * @return mixed */
        public function getNomeComune(){
            return $this->nome_comune;
        }

        /**
         * @param mixed $nome_comune */
        public function setNomeComune($nome_comune): void{
            $this->nome_comune = $nome_comune;
        }

        /**
         * @return int|null */
        public function getPesoMedio(): ?int{
            return $this->peso_medio;
        }

        /**
         * @param int|null $peso_medio */
        public function setPesoMedio(?int $peso_medio): void{
            $this->peso_medio = $peso_medio;
        }

        /**
         * @return int|null */
        public function getAltezzaMedia(): ?int{
            return $this->altezza_media;
        }

        /**
         * @param int|null $altezza_media */
        public function setAltezzaMedia(?int $altezza_media): void{
            $this->altezza_media = $altezza_media;
        }

        /**
         * @return string|null */
        public function getDescrizione(): ?string{
            return $this->descrizione;
        }

        /**
         * @param string|null $descrizione */
        public function setDescrizione(?string $descrizione): void{
            $this->descrizione = $descrizione;
        }

        /**
         * @return string|null */
        public function getImmagine(): ?string{
            return $this->immagine;
        }

        /**
         * @param string|null $immagine */
        public function setImmagine(?string $immagine): void{
            $this->immagine = $immagine;
        }

        /**
         * @return ConservazioneVO */
        public function getConservazioneVO(): ConservazioneVO{
            return $this->conservazioneVO;
        }

        /**
         * @param ConservazioneVO $conservazioneVO */
        public function setConservazioneVO(ConservazioneVO $conservazioneVO): void{
            $this->conservazioneVO = $conservazioneVO;
        }

        /**
         * @return GenereVO */
        public function getGenereVO(): GenereVO{
            return $this->genereVO;
        }

        /**
         * @param GenereVO $genereVO */
        public function setGenereVO(GenereVO $genereVO): void{
            $this->genereVO = $genereVO;
        }


        public function arrayDump(): array {

            $result = get_object_vars($this);

            /** Togliamo gli array.*/
            unset($result['genereVO']);
            unset($result['conservazioneVO']);

            foreach ($this->genereVO->arrayDump() as $key => $value)
                $result["g_$key"] = $value;

            foreach ($this->conservazioneVO->arrayDump() as $key => $value)
                $result["c_$key"] = $value;

            return $result;
        }
    }