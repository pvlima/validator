<?php

return [
    'required' => function($value, $compare){
        return true;
    },
    'email' => function($value, $compare){
        if(!filter_var($value[1], FILTER_VALIDATE_EMAIL))
            throw new UnexpectedValueException("O email \"{$value[1]}\" não tem um formato válido");
    },
    'min' => function($value, $compare){
        if(is_int($value[1])){
            if((int)$value[1] < $compare)
                throw new UnexpectedValueException("O valor de \"{$value[0]}\" não pode ser menor que {$compare}");
                
        } else {
            if(strlen($value[1]) < $compare)
                throw new UnexpectedValueException("O valor de \"{$value[0]}\" deve ter no mínimo {$compare} caracteres");
        }
    },
    'max' => function($value, $compare){
        if(is_int($value[1])){
            if((int)$value[1] > $compare)
                throw new UnexpectedValueException("O valor de \"{$value[0]}\" não pode ser maior que {$compare}");
                
        } else {
            if(strlen($value[1]) > $compare)
                throw new UnexpectedValueException("O valor de \"{$value[0]}\" deve ter no máximo {$compare} caracteres");
        }
    },
    'not_empty' => function($value, $compare){
        if(empty($value[1]))
            throw new UnexpectedValueException("O valor de \"{$value[0]}\" não pode ser vazio");
            
    },
    'string' => function($value, $compare){
        if(!is_string($value[1]))
            throw new UnexpectedValueException("O valor de \"{$value[0]}\" deve ser uma string");
    },
    'int' => function($value, $compare){
        if(!is_int($value[1]))
            throw new UnexpectedValueException("O valor de \"{$value[0]}\" deve ser do tipo int (integer)");
    },
    'numeric' => function($value, $compare){
        if(!is_numeric($value[1]))
            throw new UnexpectedValueException("O valor de \"{$value[0]}\" deve ser um número");
    },
    'cpf' => function ($value, $compare) {
 
        $cpf = $value[1];
        if(empty($cpf))
            throw new UnexpectedValueException("CPF inválido");
     
        // Elimina possivel mascara
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
         
        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cpf) != 11) {
            throw new UnexpectedValueException("CPF inválido");
        }
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' || 
            $cpf == '11111111111' || 
            $cpf == '22222222222' || 
            $cpf == '33333333333' || 
            $cpf == '44444444444' || 
            $cpf == '55555555555' || 
            $cpf == '66666666666' || 
            $cpf == '77777777777' || 
            $cpf == '88888888888' || 
            $cpf == '99999999999') {
                throw new UnexpectedValueException("CPF inválido");
         // Calcula os digitos verificadores para verificar se o
         // CPF é válido
         } else {   
             
            for ($t = 9; $t < 11; $t++) {
                 
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) throw new UnexpectedValueException("CPF inválido");
            }

            return true;
        }
    }
];
