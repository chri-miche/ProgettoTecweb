<?php

require_once 'AdminTable.php';
require_once 'AdminWelcomePage.php';
require_once 'AdminForm.php';
require_once 'ConfirmDelete.php';
require_once 'utenti/UtentiAdmin.php';
require_once 'utenti/UtenteForm.php';
require_once 'LandingPage.php';
require_once 'SuccessLandingPage.php';
require_once 'FailureLandingPage.php';
require_once 'utenti/ConfirmDeleteUtente.php';

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

    private $component;
    private $manage;
    private $voices = [];

    public function __construct($manage = null, $operation = "list", $keys = null, $data = null)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/AdminPanel.xhtml'));

        $this->manage = $manage;
        foreach (AdminPanel::$entities as $key => $value) {
            $this->voices[] = [$value, "admin.php?manage=$key"];
        }

        $this->component = null;

        if ($manage === 'utente') {
            if ($operation === 'list') {
                $this->component = new UtentiAdmin();
            } elseif ($operation === 'update') {
                if (count($data) > 0) {
                    $dao = new UserDAO();
                    $vo = $dao->get($data['id']);
                    $vo->setNome($data['nome']);
                    $vo->setAdmin($data['is_admin']);

                    if ($dao->save($vo)) {
                        $this->component = new SuccessLandingPage($manage);
                    } else {
                        // TODO errors in realtà questa cosa non può andare in errore
                        $this->component = new UtenteForm((new UserDAO())->get($keys['id']));
                    }
                } else {
                    $this->component = new UtenteForm((new UserDAO())->get($keys['id']));
                }

            } elseif ($operation === 'confirm-delete') {
                $this->component = new ConfirmDeleteUtente((new UserDAO())->get($keys['id']));
            } elseif ($operation === 'delete') {

                $dao = new UserDAO();
                $vo = $dao->get($keys['id']);
                if ($dao->delete($vo)) {
                    $this->component = new SuccessLandingPage($manage);
                } else {
                    $this->component = new FailureLandingPage($manage);
                }
            }

        } else {
            if (isset($manage)) {

                switch ($operation) {

                    case "update":
                    case "create":
                        if (count($data) > 0) {
                            // insert nuovi dati
                            $persistent = new Persistent($manage, $data);
                            $success = $persistent->commitFromProto($operation);
                            if ($success) {
                                $this->component = new class($manage) extends PageFiller {
                                    private $manage;

                                    public function __construct($manage)
                                    {
                                        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/LandingPage.xhtml'));
                                        $this->manage = $manage;
                                    }

                                    public function resolveData()
                                    {
                                        return array(
                                            "{message}" => "Operazione eseguita con successo",
                                            "{result}" => "success",
                                            "{manage}" => $this->manage
                                        );
                                    }
                                };
                            } else {
                                $this->component = new AdminForm($manage, $keys, $persistent);
                            }
                        } else {
                            $this->component = new AdminForm($manage, $keys);
                        }
                        break;
                    case "confirm-delete":
                        $this->component = new ConfirmDelete($manage, $keys);
                        break;
                    case "delete":
                        $persistent = new Persistent($manage, $keys);
                        if ($persistent->deleteFromProto()) {
                            $this->component = new class($manage) extends PageFiller {
                                private $manage;
                                public function __construct($manage)
                                {
                                    parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/LandingPage.xhtml'));
                                    $this->manage = $manage;
                                }

                                public function resolveData()
                                {
                                    return array(
                                        "{message}" => "Cancellazione eseguita con successo",
                                        "{result}" => "success",
                                        "{manage}" => $this->manage
                                    );
                                }
                            };
                        } else {
                            $this->component = new class($manage) extends PageFiller {
                                private $manage;
                                public function __construct($manage)
                                {
                                    parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/LandingPage.xhtml'));
                                    $this->manage = $manage;
                                }

                                public function resolveData()
                                {
                                    return array(
                                        "{message}" => "Errore durante la cancellazione",
                                        "{result}" => "failure",
                                        "{manage}" => $this->manage
                                    );
                                }
                            };
                        }
                        break;
                    case "list":
                    default:

                        $this->component = new AdminTable($manage, AdminPanel::$entities[$manage]);

                        break;
                }



            } else {

                $this->component = new AdminWelcomePage();

            }
        }
    }

    public function build()
    {
        $HTML = str_replace("<menu />", (new Menu($this->manage ?? 'welcome', $this->voices))->build(), $this->baseLayout());
        $HTML = str_replace("<content />", $this->component->build(), $HTML);
        $HTML = str_replace("{table}", $this->manage, $HTML);
        return $HTML;
    }
}

?>