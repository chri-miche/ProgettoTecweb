<?php

require_once __ROOT__ . '\model\VO\VO.php';
class TagVO implements  VO{

    /**@var int | null*/
    private $id;
    /**@var string |null */
    private $label;

    /**
     * TagVO constructor.
     * @param int|null $id
     * @param string|null $label
     */

    public function __construct(?int $id = null, ?string $label = null) {
        $this->id = $id; $this->label = $label;
    }

    public function __get($name){ return $this->$name ?? null;}

    public function varDumps(bool $id = false): array {

        $array = get_object_vars($this);
        if($id) unset($array['id']);
        return array_values($array);

    }

    public function smartDump(bool $id = false): array {

        return $this->varDumps($id);
    }

    /**
     * @return int|null */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null */
    public function getLabel(): ?string {
        return $this->label;
    }

    /**
     * @param string|null $label  */
    public function setLabel(?string $label): void {
        $this->label = $label;
    }


}