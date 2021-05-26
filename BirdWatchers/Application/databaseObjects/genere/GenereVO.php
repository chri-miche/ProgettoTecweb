<?php

require_once __DIR__ . "/../VO.php";
require_once __DIR__ . "/../famiglia/FamigliaVO.php";

class GenereVO implements VO{

    /**@var int*/
    private $id;
    /**@var string*/
    private $nome_scientifico;
    /**@var FamigliaVO*/
    private $famigliaVO;

    /*** GenereVO constructor.
     * @param int|null $id
     * @param string|null $nome_scientifico
     * @param FamigliaVO|null $famigliaVO */
    public function __construct(int $id = null, string $nome_scientifico = null, FamigliaVO $famigliaVO = null) {

        $this->id = $id; $this->nome_scientifico = $nome_scientifico;
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
        return $this->nome_scientifico;
    }

    /**
     * @param string $nome_scientifico
     */
    public function setNomeScientifico(string $nome_scientifico): void{
        $this->nome_scientifico = $nome_scientifico;
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

    public function arrayDump(): array {
        $result = get_object_vars($this);

        /** Togliamo gli array.*/
        unset($result['famigliaVO']);

        foreach ($this->famigliaVO->arrayDump() as $key => $value)
            $result["f_$key"] = $value;

        return $result;
    }
}

