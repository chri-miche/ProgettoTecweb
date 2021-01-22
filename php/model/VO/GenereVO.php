<?php


    class GenereVO implements VO{

        /**@var int*/
        private $id;
        /**@var string*/
        private $nomeScientifico;
        /**@var FamigliaVO*/
        private $famigliaVO;

        /*** GenereVO constructor.
         * @param int|null $id
         * @param string|null $nomeScientifico
         * @param FamigliaVO|null $famigliaVO */
        public function __construct(int $id = null, string $nomeScientifico = null, FamigliaVO $famigliaVO = null) {

            $this->id = $id; $this->nomeScientifico = $nomeScientifico;
            $this->famigliaVO = $famigliaVO ?? new FamigliaVO();

        }

        public function __get($name){ return $this->$name ?? null; }

        public function varDumps(bool $id = false) : array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);

            return array_values($array);

        }


        /** Ritorna id del genere.
         * @return int */
        public function getId(): ?int{
            return $this->id;
        }

        /**
         * @return string */
        public function getNomeScientifico(): ?string{
            return $this->nomeScientifico;
        }

        /**
         * @param string $nomeScientifico
         */
        public function setNomeScientifico(string $nomeScientifico): void{
            $this->nomeScientifico = $nomeScientifico;
        }

        /**
         * @return FamigliaVO */
        public function getFamigliaVO(): FamigliaVO{
            return $this->famigliaVO;
        }

        /**
         * @param FamigliaVO $famigliaVO */
        public function setFamigliaVO(FamigliaVO $famigliaVO): void{
            $this->famigliaVO = $famigliaVO;
        }


        public function smartDump(bool $id = false): array {

            $data = get_object_vars($this);

            if($id) unset($data['id']);
            $data['famigliaVO'] = $this->famigliaVO->getId();

            return array_values($data);

        }
    }

