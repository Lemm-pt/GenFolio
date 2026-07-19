<?php
/**
 * Horario Model - Gestão de horários de atendimento
 * 
 * @package SevenLux
 */

namespace core\models;

use core\classes\Database;

class Horario
{
    private $db;
    private $cliente_id;
    private $cache = [];
    private $ativo = true;
    private $horarioAtivo = true; // Ativo global para este cliente

    public function __construct($cliente_id = null)
    {
        $this->db = new Database();
        $this->cliente_id = $cliente_id ?? (defined('CLIENTE_ID') ? CLIENTE_ID : 1);
        $this->carregarHorarios();
    }

    /**
     * Carrega todos os horários do cliente para cache
     */
    private function carregarHorarios()
    {
        $result = $this->db->select(
            "SELECT dia_semana, abertura, fechamento, ativo FROM sevenlux_horarios 
             WHERE cliente_id = :cliente_id",
            [':cliente_id' => $this->cliente_id]
        );

        if ($result) {
            foreach ($result as $row) {
                $this->cache[$row->dia_semana] = [
                    'abertura' => $row->abertura,
                    'fechamento' => $row->fechamento,
                    'ativo' => (bool)$row->ativo
                ];
            }
            
            // Verificar se o horário está ativo (primeiro dia, já que todos têm o mesmo valor)
            $primeiroDia = reset($this->cache);
            $this->horarioAtivo = $primeiroDia ? $primeiroDia['ativo'] : true;
        } else {
            // Se não houver registos, criar com valores padrão
            $this->criarRegistosPadrao();
            $this->horarioAtivo = true;
        }
    }

    /**
     * Cria registos de horário padrão para o cliente
     */
    private function criarRegistosPadrao()
    {
        $padrao = [
            'segunda' => ['09:00', '18:00'],
            'terca' => ['09:00', '18:00'],
            'quarta' => ['09:00', '18:00'],
            'quinta' => ['09:00', '18:00'],
            'sexta' => ['09:00', '18:00'],
            'sabado' => ['fechado', null],
            'domingo' => ['fechado', null]
        ];

        foreach ($padrao as $dia => $horario) {
            $this->setHorario($dia, $horario[0], $horario[1], true);
        }
    }

    /**
     * Verifica se o horário está ativo globalmente
     * 
     * @return bool
     */
    public function isAtivo()
    {
        return $this->horarioAtivo;
    }

    /**
     * Ativa ou desativa o horário globalmente para este cliente
     * 
     * @param bool $ativo
     * @return bool
     */
    public function setAtivo($ativo)
    {
        $ativoValue = $ativo ? 1 : 0;
        
        // Atualizar todos os dias do cliente
        $this->db->update(
            "UPDATE sevenlux_horarios 
             SET ativo = :ativo, updated_at = NOW()
             WHERE cliente_id = :cliente_id",
            [
                ':ativo' => $ativoValue,
                ':cliente_id' => $this->cliente_id
            ]
        );
        
        // Atualizar cache
        foreach ($this->cache as $dia => &$info) {
            $info['ativo'] = (bool)$ativo;
        }
        
        $this->horarioAtivo = (bool)$ativo;
        
        // Log da ação
        \core\classes\Logger::log('horario_ativo', "Horário " . ($ativo ? 'ativado' : 'desativado') . " para cliente ID: " . $this->cliente_id, $this->cliente_id);
        
        return true;
    }

    /**
     * Obtém o horário de um dia específico
     * 
     * @param string $dia segunda|terca|quarta|quinta|sexta|sabado|domingo
     * @return array|null
     */
    public function getHorario($dia)
    {
        return $this->cache[$dia] ?? null;
    }

    /**
     * Obtém todos os horários
     * 
     * @return array
     */
    public function getAll()
    {
        $dias = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
        $labels = [
            'segunda' => 'Segunda-feira',
            'terca' => 'Terça-feira',
            'quarta' => 'Quarta-feira',
            'quinta' => 'Quinta-feira',
            'sexta' => 'Sexta-feira',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo'
        ];

        $result = [];
        foreach ($dias as $dia) {
            $horario = $this->getHorario($dia);
            if ($horario) {
                $result[$dia] = [
                    'label' => $labels[$dia],
                    'dia' => $dia,
                    'abertura' => $horario['abertura'] ?? 'fechado',
                    'fechamento' => $horario['fechamento'] ?? '',
                    'status' => ($horario['abertura'] ?? 'fechado') === 'fechado' ? 'fechado' : 'aberto',
                    'display' => ($horario['abertura'] ?? 'fechado') === 'fechado' 
                        ? 'Fechado' 
                        : ($horario['abertura'] ?? '') . ' - ' . ($horario['fechamento'] ?? ''),
                    'ativo' => $horario['ativo']
                ];
            }
        }

        return $result;
    }

    /**
     * Atualiza o horário de um dia
     * 
     * @param string $dia
     * @param string $abertura
     * @param string|null $fechamento
     * @param bool $ativo
     * @return bool
     */
    public function setHorario($dia, $abertura, $fechamento = null, $ativo = true)
    {
        // Validar dia
        $diasValidos = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
        if (!in_array($dia, $diasValidos)) {
            return false;
        }

        // Se abertura for 'fechado' ou vazio, fechamento fica NULL
        if ($abertura === 'fechado' || empty($abertura)) {
            $abertura = 'fechado';
            $fechamento = null;
        }

        // Verificar se já existe
        $existe = $this->db->select(
            "SELECT id FROM sevenlux_horarios 
             WHERE cliente_id = :cliente_id AND dia_semana = :dia",
            [':cliente_id' => $this->cliente_id, ':dia' => $dia]
        );

        if ($existe && !empty($existe)) {
            // Atualizar
            $this->db->update(
                "UPDATE sevenlux_horarios 
                 SET abertura = :abertura, fechamento = :fechamento, ativo = :ativo, updated_at = NOW()
                 WHERE cliente_id = :cliente_id AND dia_semana = :dia",
                [
                    ':cliente_id' => $this->cliente_id,
                    ':dia' => $dia,
                    ':abertura' => $abertura,
                    ':fechamento' => $fechamento,
                    ':ativo' => $ativo ? 1 : 0
                ]
            );
        } else {
            // Inserir
            $this->db->insert(
                "INSERT INTO sevenlux_horarios (cliente_id, dia_semana, abertura, fechamento, ativo) 
                 VALUES (:cliente_id, :dia, :abertura, :fechamento, :ativo)",
                [
                    ':cliente_id' => $this->cliente_id,
                    ':dia' => $dia,
                    ':abertura' => $abertura,
                    ':fechamento' => $fechamento,
                    ':ativo' => $ativo ? 1 : 0
                ]
            );
        }

        // Atualizar cache
        $this->cache[$dia] = [
            'abertura' => $abertura,
            'fechamento' => $fechamento,
            'ativo' => $ativo
        ];

        return true;
    }

   /**
     * Verifica se a empresa está aberta neste momento
     * 
     * @return array
     */
    public function verificarStatusAgora()
    {
        $labels = [
            'segunda' => 'Segunda',
            'terca' => 'Terça',
            'quarta' => 'Quarta',
            'quinta' => 'Quinta',
            'sexta' => 'Sexta',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo'
        ];

        $diasSemana = [
            1 => 'segunda',
            2 => 'terca',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sabado',
            7 => 'domingo'
        ];

        $diaAtual = (int)date('N');
        $horaAtual = date('H:i');
        $diaKey = $diasSemana[$diaAtual] ?? 'segunda';

        // 1. Se o horário não estiver ativo globalmente
        if (!$this->isAtivo()) {
            return [
                'status' => 'fechado',
                'status_icon' => '🔴',
                'status_text' => 'Fechado',
                'status_class' => 'fechado',
                'mensagem' => 'Horário desativado',
                'dia' => $diaKey,
                'dia_label' => $labels[$diaKey] ?? $diaKey, // CORREÇÃO
                'horario' => 'Fechado',
                'abertura' => null,
                'fechamento' => null,
                'proximo' => null,
                'proximo_texto' => null
            ];
        }

        $horario = $this->getHorario($diaKey);
        
        // 2. Se o dia atual estiver desativado ou explicitamente fechado
        if (!$horario || !$horario['ativo'] || $horario['abertura'] === 'fechado' || empty($horario['abertura'])) {
            $proximo = $this->proximoHorarioAbertura($diaKey);
            return [
                'status' => 'fechado',
                'status_icon' => '🔴',
                'status_text' => 'Fechado',
                'status_class' => 'fechado',
                'mensagem' => 'Fechado hoje',
                'dia' => $diaKey,
                'dia_label' => $labels[$diaKey] ?? $diaKey, // CORREÇÃO
                'horario' => 'Fechado',
                'abertura' => null,
                'fechamento' => null,
                'proximo' => $proximo,
                'proximo_texto' => $proximo ? "Próxima abertura: <strong>{$proximo}</strong>" : null
            ];
        }

        $aberturaTimestamp = strtotime($horario['abertura']);
        $fechamentoTimestamp = strtotime($horario['fechamento']);
        $horaAtualTimestamp = strtotime($horaAtual);

        $estaAberto = ($horaAtualTimestamp >= $aberturaTimestamp && $horaAtualTimestamp <= $fechamentoTimestamp);

        // 3. Retorno quando há horário definido para hoje (pode estar aberto ou fechado dependendo da hora atual)
        return [
            'status' => $estaAberto ? 'aberto' : 'fechado',
            'status_icon' => $estaAberto ? '🟢' : '🔴',
            'status_text' => $estaAberto ? 'Aberto' : 'Fechado',
            'status_class' => $estaAberto ? 'aberto' : 'fechado',
            'mensagem' => $estaAberto ? 'Aberto agora' : 'Fechado agora',
            'dia' => $diaKey,
            'dia_label' => $labels[$diaKey] ?? $diaKey,
            'horario' => $horario['abertura'] . ' - ' . $horario['fechamento'],
            'abertura' => $horario['abertura'],
            'fechamento' => $horario['fechamento'],
            'proximo' => $estaAberto ? null : $this->proximoHorarioAbertura($diaKey),
            'proximo_texto' => $estaAberto ? "Aberto até às <strong>{$horario['fechamento']}</strong>" : ($this->proximoHorarioAbertura($diaKey) ? "Próxima abertura: <strong>{$this->proximoHorarioAbertura($diaKey)}</strong>" : null)
        ];
    }

    /**
     * Obtém o horário formatado para exibição na secção da home
     * 
     * @return array
     */
    public function getHorarioParaHome()
    {
        // Se o horário não estiver ativo globalmente, retorna vazio
        if (!$this->isAtivo()) {
            return [
                'hoje' => [
                    'status' => 'fechado',
                    'status_icon' => '🔴',
                    'status_text' => 'Fechado',
                    'status_class' => 'fechado',
                    'mensagem' => 'Horário desativado',
                    'dia' => 'segunda',
                    'horario' => 'Fechado',
                    'abertura' => null,
                    'fechamento' => null,
                    'proximo' => null,
                    'proximo_texto' => null
                ],
                'dias' => []
            ];
        }
        
        $hoje = $this->verificarStatusAgora();
        $todos = $this->getAll();
        
        // Ordenar os dias corretamente
        $ordem = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
        $diasOrdenados = [];
        foreach ($ordem as $dia) {
            if (isset($todos[$dia]) && $todos[$dia]['ativo']) {
                $diasOrdenados[$dia] = $todos[$dia];
            }
        }
        
        return [
            'hoje' => $hoje,
            'dias' => $diasOrdenados
        ];
    }

    /**
     * Calcula o próximo horário de abertura
     * 
     * @param string $diaAtual
     * @return string|null
     */
    private function proximoHorarioAbertura($diaAtual)
    {
        // Se o horário não estiver ativo globalmente, retorna null
        if (!$this->isAtivo()) {
            return null;
        }
        
        $diasSemana = ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'];
        $indiceAtual = array_search($diaAtual, $diasSemana);

        for ($i = 1; $i <= 7; $i++) {
            $indice = ($indiceAtual + $i) % 7;
            $dia = $diasSemana[$indice];
            $horario = $this->getHorario($dia);

            if ($horario && $horario['ativo'] && $horario['abertura'] !== 'fechado' && !empty($horario['abertura'])) {
                $labels = [
                    'segunda' => 'Segunda',
                    'terca' => 'Terça',
                    'quarta' => 'Quarta',
                    'quinta' => 'Quinta',
                    'sexta' => 'Sexta',
                    'sabado' => 'Sábado',
                    'domingo' => 'Domingo'
                ];
                return $labels[$dia] . ' às ' . $horario['abertura'];
            }
        }

        return null;
    }

    /**
     * Reseta os horários para o padrão
     */
    public function resetToDefault()
    {
        $padrao = [
            'segunda' => ['09:00', '18:00'],
            'terca' => ['09:00', '18:00'],
            'quarta' => ['09:00', '18:00'],
            'quinta' => ['09:00', '18:00'],
            'sexta' => ['09:00', '18:00'],
            'sabado' => ['fechado', null],
            'domingo' => ['fechado', null]
        ];

        foreach ($padrao as $dia => $horario) {
            $this->setHorario($dia, $horario[0], $horario[1], true);
        }
        
        // Ativar globalmente
        $this->setAtivo(true);
    }
}