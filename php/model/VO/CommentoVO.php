<?php

    require_once __ROOT__ . '\model\VO\VO.php';
    require_once __ROOT__ . '\model\VO\PostVO.php';

    class CommentoVO implements VO {

        /** @var int | null*/
        private $id;
        /** @var int | null*/
        private $userId;
        /** @var boolean */
        private $isArchived;
        /** @var string */
        private $content;
        /** @var string| null*/
        private $date;
        /** Referenced post.
         *  @var  PostVO | null*/
        private $postVO;

        /** CommentoVO constructor.
         * @param int|null $id
         * @param int|null $userId
         * @param bool $isArchived
         * @param string $content
         * @param DateTime | null $date
         * @param PostVO| null $postVO */
        public function __construct(?int $id = null, ?int $userId = null, bool $isArchived = false,
                                    string $content = '', ?string $date = null, PostVO $postVO = null) {

            $this->id = $id;
            $this->userId = $userId;
            $this->isArchived = $isArchived;
            $this->content = $content;
            $this->date = $date;
            $this->postVO = $postVO;

        }

        public function __get($name){ return $this->$name ?? null; }

        public function arrayDump(): array {

            $result = get_object_vars($this);

            /** Togliamo gli array.*/
            unset($result['postVO']);

            /** @var $counter: Contatore di elementi immagine in modo da impostare un default.*/

            foreach ($this->postVO->arrayDump() as $key => $value)
                $result["p_$key"] = $value;

            return $result;

        }

        public function varDumps(bool $id = false): array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);

            return array_values($array);
        }

        public function smartDump(bool $id = false): array {

            $return = get_object_vars($this);

            if($id) unset($return['id']);
            $return['postVO'] = $this->postVO->getId();

            return array_values($return);

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
         * @return DateTime */
        public function getDate(): ?string{
            return $this->date;
        }

        /**
         * @param DateTime $date */
        public function setDate(?string $date): void{
            $this->date = $date;
        }

        /**
         * @return PostVO|null */
        public function getPostVO(): ?PostVO {
            return $this->postVO;
        }

        /**
         * @param PostVO|null $postVO */
        public function setPostVO(?PostVO $postVO): void {
            $this->postVO = $postVO;
        }

    }