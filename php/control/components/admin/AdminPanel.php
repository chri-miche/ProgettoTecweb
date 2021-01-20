<?php

require_once __ROOT__.'/control/components/admin/AdminTable.php';
require_once __ROOT__.'/control/components/admin/AdminWelcomePage.php';
require_once __ROOT__.'/control/components/admin/AdminForm.php';
require_once __ROOT__.'/control/components/admin/ConfirmDelete.php';

class AdminPanel extends Component
{

    private static $entities = array(
        "famiglia" => "Famiglia",
        "tag" => "Tag",
    );

    private $navigation;
    private $component;
    private $manage;

    public function __construct($manage = null, $operation = "list", $keys = null, $data = null)
    {
        parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/AdminPanel.xhtml'));

        $this->manage = $manage;
        $this->navigation = "";
        foreach (AdminPanel::$entities as $key => $value) {
            $this->navigation .= "<li><a href='Admin.php?manage=$key'>$value</a></li>";
        }

        $this->component = null;

        if (isset($manage)) {

            switch ($operation) {

                case "update":
                case "create":
                    if (count($data) > 0) {
                        // insert nuovi dati
                        $persistent = new Persistent($manage, $data);
                        $success = $persistent->commitFromProto($operation);
                        if ($success) {
                            $this->component = new class extends PageFiller {
                                public function __construct()
                                {
                                    parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/LandingPage.xhtml'));
                                }

                                public function resolveData()
                                {
                                    return array(
                                        "{message}" => "Operazione eseguita con successo",
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
                        $this->component = new class extends PageFiller {
                            public function __construct()
                            {
                                parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/LandingPage.xhtml'));
                            }

                            public function resolveData()
                            {
                                return array(
                                    "{message}" => "Cancellazione eseguita con successo",
                                );
                            }
                        };
                    } else {
                        $this->component = new class extends PageFiller {
                            public function __construct()
                            {
                                parent::__construct(file_get_contents(__ROOT__.'/view/modules/admin/LandingPage.xhtml'));
                            }

                            public function resolveData()
                            {
                                return array(
                                    "{message}" => "Errore durante la cancellazione",
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

    public function build()
    {
        $HTML = str_replace("{navigation}", $this->navigation, $this->baseLayout());
        $HTML = str_replace("<content />", $this->component->build(), $HTML);
        $HTML = str_replace("{table}", $this->manage, $HTML);
        return $HTML;
    }
}