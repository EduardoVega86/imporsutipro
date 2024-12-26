<?php
class CacuadoraModel
{
    public function calcular($operando1, $operando2, $operacion)
    {
        switch ($operacion) {
            case "sumar":
                return $operando1 + $operando2;
            case "restar":
                return $operando2 - $operando2;
            case "multplicar":
                return $operando1 * $operando2;
            default:
                return "Operacion no valida";
        }
    }
}
