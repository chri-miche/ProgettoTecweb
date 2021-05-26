<?php

require_once "utenti/UtenteTable.php";
require_once "utenti/UtenteRow.php";
require_once "utenti/UtenteForm.php";
require_once "utenti/ConfirmDeleteUtente.php";
require_once "SuccessLandingPage.php";
require_once "FailureLandingPage.php";
require_once "VoTable.php";
require_once "VoForm.php";
require_once "VoComponent.php";
require_once __ROOT__
    . DIRECTORY_SEPARATOR . "model"
    . DIRECTORY_SEPARATOR . "DAO"
    . DIRECTORY_SEPARATOR . "OrdineDAO.php";
require_once __ROOT__
    . DIRECTORY_SEPARATOR . "model"
    . DIRECTORY_SEPARATOR . "DAO"
    . DIRECTORY_SEPARATOR . "ConservazioneDAO.php";
require_once __ROOT__
    . DIRECTORY_SEPARATOR . "model"
    . DIRECTORY_SEPARATOR . "DAO"
    . DIRECTORY_SEPARATOR . "FamigliaDAO.php";
require_once __ROOT__
    . DIRECTORY_SEPARATOR . "model"
    . DIRECTORY_SEPARATOR . "DAO"
    . DIRECTORY_SEPARATOR . "GenereDAO.php";
require_once __ROOT__
    . DIRECTORY_SEPARATOR . "model"
    . DIRECTORY_SEPARATOR . "DAO"
    . DIRECTORY_SEPARATOR . "SpecieDAO.php";

class AdminPanel extends Component
{
    private static $entities = array(
        "utente" => "Utenti",
        "ordine" => "Ordine",
        "specie" => "Specie",
        "famiglia" => "Famiglie",
        "genere" => "Genere",
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
        parent::__construct(file_get_contents(__ROOT__ .
            DIRECTORY_SEPARATOR . 'view' .
            DIRECTORY_SEPARATOR . 'modules' .
            DIRECTORY_SEPARATOR . 'admin' .
            DIRECTORY_SEPARATOR . 'AdminPanel.xhtml'));

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
            echo $manage;
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
                    break;
                case "famiglia":
                    $dao = new FamigliaDAO();
                    break;
                case "specie":
                    $dao = new SpecieDAO();
                    break;
                case "genere":
                    $dao = new GenereDAO();
                    break;
                case "conservazione":
                    $dao = new ConservazioneDAO();
                    if (sizeof($data) > 0) {
                        // try operation
                        $errors = $this->checkConservazione($data);
                        $vo = new ConservazioneVO(...$data);
                        echo $vo->getprob_estinzione();
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
            $htmls[$way] = file_get_contents(__ROOT__ .
                DIRECTORY_SEPARATOR . 'view' .
                DIRECTORY_SEPARATOR . 'modules' .
                DIRECTORY_SEPARATOR . 'admin' .
                DIRECTORY_SEPARATOR . $table .
                DIRECTORY_SEPARATOR . $table . $way . '.xhtml');
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
            echo "Floatval di " . $data['prob_estinzione'].  " === a " . floatval($data['prob_estinzione']);
            $data['prob_estinzione'] = floatval($data['prob_estinzione']);
        }

        return $errors;
    }
}
?>