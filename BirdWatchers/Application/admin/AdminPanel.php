<?php

require_once "utenti/UtenteTable.php";
require_once "utenti/UtenteRow.php";
require_once "utenti/UtenteForm.php";
require_once "utenti/ConfirmDeleteUtente.php";
require_once "landing-page" . DIRECTORY_SEPARATOR . "SuccessLandingPage.php";
require_once "landing-page" . DIRECTORY_SEPARATOR . "FailureLandingPage.php";
require_once "VoTable.php";
require_once "VoForm.php";
require_once "VoComponent.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "databaseObjects/ordine/OrdineDAO.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "databaseObjects/conservazione/ConservazioneDAO.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "databaseObjects/famiglia/FamigliaDAO.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "databaseObjects/genere/GenereDAO.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "databaseObjects/specie/SpecieDAO.php";

class AdminPanel extends Component
{
    private static $entities = array(
        "utente" => "Utenti",
        "ordine" => "Ordine",
        "famiglia" => "Famiglie",
        "genere" => "Genere",
        "specie" => "Specie",
        "conservazione" => "Conservazione",
    );

    /**
     * @var string
     */
    private $manage;
    /**
     * @var Component
     */
    private $component;
    /**
     * @var array
     */
    private $voices;

    public function __construct(string $manage = "", string $operation = "list", array $keys = [], array $data = [])
    {
        parent::__construct(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "AdminPanel.xhtml"));

        $this->manage = $manage;

        foreach (AdminPanel::$entities as $key => $value) {
            $this->voices[] = [$value, "admin.php?manage=$key"];
        }

        $this->initialize($manage, $operation, $data, $keys);
    }

    private function initialize(string $manage, string $operation, array $data, array $keys)
    {
        $this->component = null;
        if (!isset($manage) || $this->manage == '') {
            $this->component = new AdminWelcomePage();
        } else {
            $errors = [];
            switch ($manage) {
                case "utente":
                    // caso speciale
                    $this->doUserInit($operation, $data, $keys);
                    return;
                case "ordine":
                    $dao = new OrdineDAO();
                    if (sizeof($data) > 0) {
                        $vo = new OrdineVO(empty($data['id']) ? null : $data['id'], $data['nome_scientifico']);
                    } elseif (sizeof($keys) > 0) {
                        $vo = $dao->get($keys['id']);
                    } else {
                        $vo = new OrdineVO();
                    }
                    break;
                case "famiglia":
                    $dao = new FamigliaDAO();
                    if (sizeof($data) > 0) {
                        $errors = $this->checkFamiglia($data);
                        $vo = new FamigliaVO(empty($data['id']) ? null : $data['id'],
                            $data['nome_scientifico'],
                            $data['ordineVO']);
                    } elseif (sizeof($keys) > 0) {
                        $vo = $dao->get($keys['id']);
                    } else {
                        $vo = new FamigliaVO();
                    }
                    break;
                case "genere":
                    $dao = new GenereDAO();
                    if (sizeof($data) > 0) {
                        $errors = $this->checkGenere($data);
                        $vo = new GenereVO(
                            empty($data['id']) ? null : $data['id'],
                            $data['nome_scientifico'],
                            $data['famigliaVO']
                        );
                    } elseif (sizeof($keys) > 0) {
                        $vo = $dao->get($keys['id']);
                    } else {
                        $vo = new GenereVO();
                    }
                    break;
                case "specie":
                    $dao = new SpecieDAO();
                    if (sizeof($data) > 0) {
                        $this->savePhoto($data);
                        $errors = $this->checkSpecie($data);
                        $vo = new SpecieVO(empty($data['id']) ? null : $data['id'],
                            $data['nome_scientifico'],
                            $data['nome_comune'],
                            $data['peso_medio'],
                            $data['altezza_media'],
                            $data['descrizione'],
                            $data['immagine'],
                            $data['genereVO'],
                            $data['conservazioneVO']);
                    } elseif (sizeof($keys) > 0) {
                        $vo = $dao->get($keys['id']);
                    } else {
                        $vo = new SpecieVO();
                    }
                    break;
                case "conservazione":
                    $dao = new ConservazioneDAO();
                    if (sizeof($data) > 0) {
                        // try operation
                        $errors = $this->checkConservazione($data);
                        $vo = new ConservazioneVO($data['id'],
                            $data['nome'],
                            $data['prob_estinzione'],
                            $data['descrizione']);
                    } elseif (sizeof($keys) > 0) {
                        // update
                        $vo = $dao->get($keys['id']);
                    } else {
                        // create
                        $vo = new ConservazioneVO();
                    }
                    break;
                default:
                    throw new Exception("Tabella non riconosciuta");

            }

            $htmls = $this->htmlsFor($manage);

            switch ($operation) {
                case "delete":
                    if ($dao->delete($vo)) {
                        $this->component = new SuccessLandingPage($manage);
                    } else {
                        $this->component = new FailureLandingPage($manage);
                    }
                    break;
                case "confirm-delete":
                    $this->component = new VoComponent($htmls['confirmdelete'], $vo);
                    break;
                case "create":
                case "update":
                    if (sizeof($data) > 0 && sizeof($errors) == 0) {
                        // operation successful
                        if ($dao->save($vo)) {
                            $this->component = new SuccessLandingPage($manage);
                        } else {
                            $this->component = new FailureLandingPage($manage);
                        }
                    } else {
                        $this->component = new VoForm($htmls['form'], $vo, $errors, $operation);
                    }
                    break;
                case "list":
                default:
                    $this->component = new VoTable($htmls, $dao);
            }
        }
    }

    private function doUserInit(string $operation, array $data, array $keys)
    {
        if ($operation === 'list') {
            $this->component = new UtenteTable();
        } elseif ($operation === 'update') {
            if (count($data) > 0) {
                $dao = new UserDAO();
                $vo = $dao->get($data['id']);
                $vo->setNome($data['nome']);
                $vo->setAdmin($data['is_admin']);

                if ($dao->save($vo)) {
                    $this->component = new SuccessLandingPage('utente');
                } else {
                    $this->component = new UtenteForm((new UserDAO())->get($keys['id']));
                }
            } else {
                $this->component = new UtenteForm((new UserDAO())->get($keys['id']));
            }

        } else {
            throw new Exception("Operazione non permessa sulla tabella utente");
        }
    }

    private function htmlsFor(string $table)
    {
        $htmls = [];
        $ways = ['table', 'form', 'row', 'confirmdelete'];
        foreach ($ways as $way) {
            $htmls[$way] = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .
                $table . DIRECTORY_SEPARATOR .
                $table . $way . '.xhtml');
        }
        return $htmls;
    }

    public function build()
    {
        $HTML = str_replace("<menu />", (new Menu($this->manage != '' ? $this->manage : 'welcome', $this->voices))->build(), $this->baseLayout());
        $HTML = str_replace("<content />", $this->component->build(), $HTML);
        $HTML = str_replace("{table}", $this->manage, $HTML);
        return $HTML;
    }

    private function checkConservazione(&$data)
    {
        $errors = [];
        if (strlen($data['id']) !== 2) {
            $errors['id'] = "La lunghezza dell'id deve essere uguale a 2.";
            $vo = new ConservazioneVO(...$data);
            if ((new ConservazioneDAO())->checkId($vo)) {
                $errors['id'] = "L'id inserito è già usato";
            }
        }
        if (
            !is_numeric($data['prob_estinzione']) ||
            floatval($data['prob_estinzione']) > 1 ||
            floatval($data['prob_estinzione']) < 0
        ) {
            $errors['prob_estinzione'] = "La probabilità di estinzione deve essere un numero compresto tra 0 e 1.";
        } else {
            $data['prob_estinzione'] = floatval($data['prob_estinzione']);
        }

        return $errors;
    }

    private function checkFamiglia(array &$data)
    {
        $errors = [];

        $ordinevo = (new OrdineDAO())->get($data['ord_id']);
        $data['ordineVO'] = $ordinevo;
        if ($ordinevo->getId() === null) {
            $errors['o_id'] = "L'identificativo dell'ordine non è valido. Si prega di inserire un ordine nel catalogo.";
        } else {
            unset($data['ord_id']);
        }

        return $errors;
    }

    private function checkGenere(array &$data)
    {
        $errors = [];

        $famigliavo = (new FamigliaDAO())->get($data['f_id']);

        unset($data['f_id']);
        if ($famigliavo->getId() === null) {
            $errors['f_id'] = "L'identificativo della famiglia non è presente nel catalogo.";
        } else {
            $data['famigliaVO'] = $famigliavo;
        }

        return $errors;
    }

    private function checkSpecie(array &$data)
    {
        $errors = [];

        $generevo = (new GenereDAO())->get($data['g_id']);

        unset($data['g_id']);
        if ($generevo->getId() === null) {
            $errors['g_id'] = "L'identificativo del genere non è valido. Si prega di scegliere un genere presente nel catalogo";
        } else {
            $data['genereVO'] = $generevo;
        }

        $conservazionevo = (new ConservazioneDAO())->get($data['c_id']);

        unset($data['c_id']);
        if ($conservazionevo->getId() === null) {
            $errors['c_id'] = "Il codice di conservazione non è valido: selezionarne uno catalogato.";
        } else {
            $data['conservazioneVO'] = $conservazionevo;
        }

        return $errors;
    }

    private function savePhoto(&$data) {

        $tmp_name = $data['file_immagine']['tmp_name'];

        if (empty($tmp_name)) {

            if (empty($data['id'])) {
                $data['immagine'] = '';
            } else {
                $vo = (new SpecieDAO())->get($data['id']);
                $data['immagine'] = $vo->getImmagine();
            }
        } else {
            $name = $data['file_immagine']['name'];

            $pathArray = ['catalogo', sanitize_fs_entity($data['nome_scientifico'])];

            $baseDir = "";

            foreach ($pathArray as $item) {
                $baseDir .= DIRECTORY_SEPARATOR . $item;
                if (!is_dir(__IMGROOT__ . $baseDir) && !mkdir(__IMGROOT__ . $baseDir)) {
                    throw new Exception("Non sono riuscito a creare la cartella $baseDir. Per favore, riprovare.");
                }
            }
            $proposedPath = $baseDir . DIRECTORY_SEPARATOR . $name;

            $tentativi = 0;
            while (file_exists(__IMGROOT__ . $proposedPath)) {
                $tentativi++;
                $proposedPath = $baseDir . DIRECTORY_SEPARATOR . $tentativi . $name;
            }

            // echo $proposedPath;

            if (move_uploaded_file($tmp_name, __IMGROOT__ . $proposedPath)) {
                unset($data['file_immagine']);
                $data['immagine'] = str_replace('\\', '/',"res" . $proposedPath);
            } else {
                throw new Exception("Non sono riuscito a creare il file $proposedPath.");
            }
        }
    }
}
?>