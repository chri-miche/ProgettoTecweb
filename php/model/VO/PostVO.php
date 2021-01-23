<?php
    require_once __ROOT__ . '\model\VO\VO.php';

    class PostVO implements VO {

        /** @var int | null*/
        private $id;
        /** @var int | null*/
        private $userId;
        /** @var boolean */
        private $isArchived;
        /** @var string */
        private $content;
        /** @var string | null*/
        private $date;
        /** @var string */
        private $title;
        /** @var array */
        private $immagini;
        /** @var array*/
        private $arrayTagVO;

        /** PostVO constructor.
         * @param int|null $id
         * @param int|null $userId
         * @param bool $isArchived
         * @param string $content
         * @param string | null $date
         * @param string $title
         * @param array $immagini
         * @param  array $arrayTagVO*/
        public function __construct(?int $id = null, ?int $userId = null, bool $isArchived = false, string $content = '',
                                    ?string $date = null, string $title = '', array $immagini = array(), array $arrayTagVO = array()){
            $this->id = $id;
            $this->userId = $userId;
            $this->isArchived = $isArchived;
            $this->content = $content;
            $this->date = $date;
            $this->title = $title;
            $this->immagini = $immagini;
            $this->arrayTagVO = $arrayTagVO;
        }

        public function __get($name){ return $this->$name ?? null; }

        public function arrayDump() : array{

            $result = get_object_vars($this);
            unset($result['arrayTagVO']);
            return $result;

        }

        public function varDumps(bool $id = false): array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);

            return array_values($array);
        }

        public function smartDump(bool $id = false): array {

            $array = get_object_vars($this);

            unset($array['immagini']);
            unset($array['arrayTagVO']);

            if($id) unset($array['id']);

            return array_values($array);

        }

        /**
         * @return int|null */
        public function getId(): ?int{
            return $this->id;
        }

        /**
         * @return int|null */
        public function getUserId(): ?int{
            return $this->userId;
        }

        /**
         * @param int|null $userId */
        public function setUserId(?int $userId): void{
            $this->userId = $userId;
        }

        /**
         * @return bool */
        public function isArchived(): bool{
            return $this->isArchived;
        }

        /**
         * @param bool $isArchived */
        public function setIsArchived(bool $isArchived): void{
            $this->isArchived = $isArchived;
        }

        /**
         * @return string */
        public function getContent(): string{
            return $this->content;
        }

        /**
         * @param string $content */
        public function setContent(string $content): void{
            $this->content = $content;
        }

        /**
         * @return string */
        public function getDate(): ?string{
            return $this->date;
        }

        /**
         * @param string $date */
        public function setDate(string $date): void{
            $this->date = $date;
        }

        /**
         * @return string */
        public function getTitle(): string{
            return $this->title;
        }

        /**
         * @param string $title */
        public function setTitle(string $title): void {
            $this->title = $title;
        }

        /**
         * @return array */
        public function getImmagini(): array{
            return $this->immagini;
        }

        /**
         * @param array $immagini */
        public function setImmagini(array $immagini): void{
            $this->immagini = $immagini;
        }

        /**
         * @return array
         */
        public function getArrayTagVO(): array{
            return $this->arrayTagVO;
        }

        /**
         * @param array $arrayTagVO
         */
        public function setArrayTagVO(array $arrayTagVO): void{
            $this->arrayTagVO = $arrayTagVO;
        }


    }