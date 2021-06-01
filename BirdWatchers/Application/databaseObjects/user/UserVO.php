<?php

require_once __DIR__ . "/../VO.php";

class UserVO implements VO {

    private $id;
    private $nome;
    private $email;
    private $password;
    private $immagine;
    private $admin;

    /**
     * @param int|null $id Id dell utente.
     * @param string|null $nome Nome dell utente.
     * @param string|null $email Email dell utente.
     * @param string|null $password Password dell utente.
     * @param bool $admin Se l utente è un amministratore
     * @param string $immagine se l utente ha immagine
     */
    public function __construct(?int $id = null, ?string $nome = null, ?string $email = null,
                                ?string $password = null, string $immagine = 'res/Images/userdefault.png', bool $admin = false) {

        $this->id = $id;
        $this->admin = $admin;
        $this->nome = $nome;
        $this->email = $email;
        $this->password = $password;
        $this->immagine = $immagine;

    }

    public function __get($name) {
        return $this->$name ?? null;
    }

    public function varDumps(bool $id = false): array {

        $array = get_object_vars($this);
        if ($id) unset($array['id']);

        return array_values($array);

    }

    /*** @param bool $admin
     * @return UserVO
     */
    public function setAdmin(bool $admin): void {
        $this->admin = $admin;
    }

    /** @param string $nome
     * @return UserVO
     */
    public function setNome(?string $nome): void {
        $this->nome = $nome;
    }

    /*** @param string $email
     * @return UserVO
     */
    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    /** @param string $password
     * @return UserVO
     */
    public function setPassword(?string $password): void {
        $this->password = $password;
    }

    /*** @param string $immagine
     * @return UserVO
     */
    public function setImmagine(string $immagine): void {
        $this->immagine = $immagine;
    }

    /*** @return int|null */
    public function getId(): ?int {
        return $this->id;
    }

    /*** @return bool */
    public function isAdmin(): bool {
        return $this->admin;
    }

    /*** @return string */
    public function getNome(): ?string {
        return $this->nome;
    }

    /*** @return string */
    public function getEmail(): ?string {
        return $this->email;
    }

    /*** @return string */
    public function getPassword(): ?string {
        return $this->password;
    }

    /** * @return string */
    public function getImmagine(): string {
        return $this->immagine;
    }


    public function smartDump(bool $id = false): array {

        $data = get_object_vars($this);
        if ($id) unset($data['id']);
        return array_values($data);

    }

    public function arrayDump(): array {

        return get_object_vars($this);

    }
}