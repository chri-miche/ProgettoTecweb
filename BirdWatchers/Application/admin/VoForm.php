<?php


class VoForm extends Component {
    /*** @var VO */
    private $vo;
    /*** @var array */
    private $errors;
    /*** @var string */
    private $operation;

    public function __construct(string $html, VO $vo, array $errors, string $operation) {
        parent::__construct($html);
        $this->vo = $vo;
        $this->errors = $errors;
        $this->operation = $operation;
    }

    public function resolveData() {
        $values = [];
        $values['{operation}'] = $this->operation;
        $values['{key_readonly}'] = $this->operation === 'update' ? 'readonly="readonly"' : '';

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

