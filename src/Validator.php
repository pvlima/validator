<?php

namespace Plugins\Validator;

/**
 * Componente de validação de entradas
 *
 * @author Paulo Vitor <pv.lima02@gmail.com>
 * @link http://pvlima.com.br/
 */
class Validator
{

    /**
     * @var array|string|int Valores a serem validados
     */
    private $values;

    /**
     * @var array Array associativo as funções de validação
     */
    private $functions;

    function __construct()
	{
        $this->functions = require __DIR__ . "/helper.php";
    }
    
    /**
     * @param array|string|int $values
     * @return $this
     */
    public function setValue($values)
    {
        $values = $this->verifyValues($values);
        $this->values = $values;
        return $this;
    }

    /**
     * Array associativo com os validators
     * @param array $options
     * 
     * @uses Validator::processValidator()
     * 
     * @throws \UnexpectedValueException
     */
	public function validate(array $options)
	{

        foreach ($options as $attr => $validator) {
            $validator = explode('|', $validator);
            if(array_key_exists($attr, $this->values)){

                $value = [$attr, $this->values[$attr]];

                $this->processValidator($validator, $value);
                
            } else {
                if(in_array('required', $validator))
                    throw new \UnexpectedValueException("O atributo \"{$attr}\" deve ser informado");
            }

        }

    }
    
    /**
     * Array associativo com os validators
     * @param array $validator
     * @param array $value
     * 
     * @return bool
     * 
     * @throws \UnexpectedValueException
     */
    private function processValidator(array $validator, array $value)
    {
        $compare = null;
        foreach ($validator as $k => $v) {
            if(preg_match('/\w:\w/', $v)){
                $array = explode(':', $v);
                $v = $array[0];
                $compare = $array[1];
            }
            if(array_key_exists($v, $this->functions))
                $this->functions[$v]($value, $compare);
        }

        return true;
    }

    /**
     * @param mixed $values
     * @return array
     * 
     * @throws \UnexpectedValueException
     */
    private function verifyValues($values)
    {
        if(is_array($values))
            return $values;
        if(is_string($values))
            return explode('|', $values);
        if(is_int($values))
            return explode('|', (string)$values);

        throw new \UnexpectedValueException("Apenas os tipos array, string e int são permitidos como parâmetros à serem validados");
        
    }

}
