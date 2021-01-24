<?php
    require_once __ROOT__ . '\model\VO\VO.php';

    class PostVO implements VO {

        /** @var int | null*/
        private $id;
        /** @var boolean */
        private $isArchived;
        /** @var string */
        private $content;
        /** @var string | null*/
        private $date;
        /** @var string */
        private $title;
        /** @var UserVO $userVO */
        private $userVO;// AUTOHR

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
        public function __construct(?int $id = null, bool $isArchived = false, string $content = '',
                ?string $date = null, string $title = '', ?UserVO $userVO = null, array $immagini = array(), array $arrayTagVO = array()){

            $this->id = $id;
            $this->isArchived = $isArchived;
            $this->content = $content;
            $this->date = $date;
            $this->title = $title;

            $this->userVO = is_null($userVO)? new UserVO() : $userVO;

            $this->immagini = $immagini;
            $this->arrayTagVO = $arrayTagVO;
        }

        public function __get($name){ return $this->$name ?? null; }

        public function arrayDump() : array{

            $result = get_object_vars($this);

            /** Togliamo gli array.*/
            unset($result['userVO']);
            unset($result['arrayTagVO']);
            unset($result['immagini']);


            /** @var $counter: Contatore di elementi immagine in modo da impostare un default.*/
            $counter = 0;

            foreach ($this->immagini as $immagine){

                $result[ $counter == 0 ? 'immagine' : "immagine_$counter"] = $immagine;
                $counter ++;

            }

            if($counter == 0) $result['immagine'] = 'default.png';

            /** Sistemazione dell'utente.*/
            $userDataToAppend = [];
            foreach ($this->userVO->arrayDump() as $key => $value)
                $userDataToAppend["u_$key"] = $value;

            for($i = 0; $i < sizeof($this->arrayTagVO); $i++){
                foreach ($this->arrayTagVO[$i]->arrayDump() as $key => $value)
                    $result["t_$i$key"] = $value;
            }

            $result = array_merge($result, $userDataToAppend);
            return $result;

        }

        public function varDumps(bool $id = false): array {

            $array = get_object_vars($this);
            if($id) unset($array['id']);
            return array_values($array);
        }

        public function smartDump(bool $id = false): array {

            $array = get_object_vars($this);

            /** Togliamo gli array.*/
            unset($array['immagini']);
            unset($array['arrayTagVO']);
            unset($array['userVO']);


            $array = array_slice($array, 0, 1, true)
                + array('userId'=> $this->userVO->getId()) + array_slice($array,1);

            if($id)
                unset($array['id']);


            return array_values($array);

        }

        /**
         * @return int|null */
        public function getId(): ?int{
            return $this->id;
        }

        /**
         * @return UserVO
         */
        public function getUserVO(): UserVO {
            return $this->userVO;
        }

        /**
         * @param UserVO $userVO
         */
        public function setUserVO(UserVO $userVO): void {
            $this->userVO = $userVO;
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