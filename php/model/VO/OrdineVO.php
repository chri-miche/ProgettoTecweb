<?php
    require_once __ROOT__ . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . "VO" . DIRECTORY_SEPARATOR . "VO.php";
    
    class OrdineVO implements VO {

        private $id;
        private $nome_scientifico;

        public function __construct(int $id = null, string $nome_scientifico = null) {
            $this->id = $id; $this->nome_scientifico = $nome_scientifico;
        }

        public function __get($name){ return $this->$name ?? null; }

        public function varDumps(bool $id = false) : array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);

            return array_values($array);

        }


        /** Getter
         * @return int|null */
        public function getId(): ?int{
            return $this->id;
        }

        /** Setter
         * @param int|null $id */
        public function setId(?int $id): void{
            $this->id = $id;
        }

        /** Getter
         * @return string|null */
        public function getNomeScientifico(): ?string{
            return $this->nome_scientifico;
        }

        /** Setter
         * @param string|null $nome_scientifico */
        public function setNomeScientifico(?string $nome_scientifico): void{
            $this->nome_scientifico = $nome_scientifico;
        }


        public function smartDump(bool $id = false): array{

            $data = get_object_vars($this);
            if($id) unset($data['id']);
            return array_values($data);

        }

        public function arrayDump(): array {

            return get_object_vars($this);

        }
    }