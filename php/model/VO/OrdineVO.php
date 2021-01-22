<?php
    require_once __ROOT__ . '\model\VO\VO.php';
    
    class OrdineVO implements VO {

        private $id;
        private $nomeScientifico;

        public function __construct(int $id = null, string $nomeScientifico = null) {
            $this->id = $id; $this->nomeScientifico = $nomeScientifico;
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
            return $this->nomeScientifico;
        }

        /** Setter
         * @param string|null $nomeScientifico */
        public function setNomeScientifico(?string $nomeScientifico): void{
            $this->nomeScientifico = $nomeScientifico;
        }


        public function smartDump(bool $id = false): array{

            $data = get_object_vars($this);
            if($id) unset($data['id']);
            return array_values($data);

        }
    }