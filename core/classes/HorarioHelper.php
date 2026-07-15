<?php
/**
 * HorarioHelper - Funções auxiliares para o horário
 * 
 * @package SevenLux
 */

namespace core\helpers;

use core\models\Configuracao;

class HorarioHelper
{
    /**
     * Verifica se o horário deve ser mostrado
     * 
     * @param int|null $cliente_id
     * @return bool
     */
    public static function deveMostrar($cliente_id = null)
    {
        if ($cliente_id === null) {
            $cliente_id = defined('CLIENTE_ID') ? CLIENTE_ID : 1;
        }
        
        $config = new Configuracao($cliente_id);
        $valor = $config->get('mostrar_horario', '1');
        
        return $valor == '1';
    }
}