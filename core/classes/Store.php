<?php
namespace core\classes;

use Exception;

class Store {
    public static function Layout($estruturas, $dados = null) {
        if(!is_array($estruturas)) throw new Exception("Coleção de estruturas inválida");
        if(!empty($dados) && is_array($dados)) extract($dados);
        foreach($estruturas as $estrutura) include("../core/views/$estrutura.php");
    }

    public static function redirect($rota = '') {
        header("Location: " . BASE_URL . "?a=$rota");
        exit;
    }

    public static function adminLogado() {
        return isset($_SESSION['admin_id']);
    }
}