<?php


class SpecieForm extends Component
{
    /**
     * @var SpecieVO
     */
    private $vo;
    /**
     * @var array
     */
    private $errors;

    public function __construct(SpecieVO $vo, array $errors)
    {
        parent::__construct(file_get_contents(__ROOT__ .
            DIRECTORY_SEPARATOR . 'view' .
            DIRECTORY_SEPARATOR . 'admin' .
            DIRECTORY_SEPARATOR . 'specie' .
            DIRECTORY_SEPARATOR . 'specieform.xhtml'));
        $this->vo = $vo;
        $this->errors = $errors;
    }

    public function resolveData()
    {
        $values = [];
        if ($this->vo->getId() === null) {
            $values['{operation}'] = 'create';
        } else {
            $values['{operation}'] = 'update';
        }

        foreach ($this->vo->arrayDump() as $key => $value) {
            $values['{' . $key . '}'] = $value;
            $values['{' . $key . '_error}'] = '';
        }

        foreach ($this->errors as $key => $error) {
            $values['{' . $key . '_error}'] = $error;
        }

        return $values;
    }
}