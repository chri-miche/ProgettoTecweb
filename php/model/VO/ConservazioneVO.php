<?php

    class ConservazioneVO implements VO {

        /** @var string | null */
        private $id;
        /** @var string | null */
        private $nome;
        /** @var float = 0*/
        private $probEstinzione;
        /**@var string | null */
        private $descrizione;

        /**
         * ConservazioneVO constructor.
         * @param string|null $id
         * @param string|null $nome
         * @param float $probEstinzione
         * @param string|null $descrizione
         */
        public function __construct(?string $id = null, ?string $nome = null,
                                    float $probEstinzione = 0, string $descrizione = null) {

            $this->id = $id;
            $this->nome = $nome;
            $this->probEstinzione = $probEstinzione;
            $this->descrizione = $descrizione;

        }

        /**
         * @return string|null */
        public function getId(): ?string{
            return $this->id;
        }

        /**
         * @param string|null $id */
        public function setId(?string $id): void{
            $this->id = $id;
        }

        /**
         * @return string|null */
        public function getNome(): ?string{
            return $this->nome;
        }

        /**
         * @param string|null $nome */
        public function setNome(?string $nome): void{
            $this->nome = $nome;
        }

        /**
         * @return float */
        public function getProbEstinzione(): float{
            return $this->probEstinzione;
        }

        /**
         * @param float $probEstinzione */
        public function setProbEstinzione(float $probEstinzione): void{
            $this->probEstinzione = $probEstinzione;
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


        public function __get($name){

            return $this->$name ?? null;

        }
        public function varDumps(bool $id = false) : array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);

            return array_values($array);

        }


        public function smartDump(bool $id = false) : array{

            $data = get_object_vars($this);

            if($id) unset($data['id']);

            return array_values($data);

        }
    }