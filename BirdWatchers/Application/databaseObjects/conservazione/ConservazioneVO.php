<?php
require_once __DIR__ . "/../VO.php";

class ConservazioneVO implements VO {

    /** @var string | null */
    private $id;
    /** @var string | null */
    private $nome;
    /** @var float = 0*/
    private $prob_estinzione;
    /**@var string | null */
    private $descrizione;

    /**
     * ConservazioneVO constructor.
     * @param string|null $id
     * @param string|null $nome
     * @param float $prob_estinzione
     * @param string|null $descrizione
     */
    public function __construct(?string $id = null, ?string $nome = null,
                                float $prob_estinzione = 0, string $descrizione = null) {

        $this->id = $id;
        $this->nome = $nome;
        $this->prob_estinzione = $prob_estinzione;
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
    public function getprob_estinzione(): float{
        return $this->prob_estinzione;
    }

    /**
     * @param float $prob_estinzione */
    public function setprob_estinzione(float $prob_estinzione): void{
        $this->prob_estinzione = $prob_estinzione;
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

    public function arrayDump(): array {
        return get_object_vars($this);
    }
}