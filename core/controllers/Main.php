<?php
namespace core\controllers;

use core\classes\Store;
use core\classes\EnviarEmail;


class Main {


 public function index() {
    $cliente_id = $_SESSION['cliente'] ?? 1;
    $config = new \core\models\Configuracao($cliente_id);
    $servicos = (new \core\models\Servicos())->listar();
    $galeria = (new \core\models\Galeria())->listar();
    $produtos = (new \core\models\Produtos())->listar();
    $publicacoes = (new \core\models\Publicacoes())->listar(3); // <-- VERIFICAR ISTO
    
    // DEBUG: Verificar se publicacoes está vindo
    if(empty($publicacoes)) {
        error_log("Publicacoes está vazio!");
    }
    
    Store::Layout([
        'layouts/html_header',
        'layouts/header',
        'home',
        'layouts/footer',
        'layouts/html_footer'
    ], [
        'config' => $config,
        'servicos' => $servicos,
        'galeria' => $galeria,
        'produtos' => $produtos,
        'publicacoes' => $publicacoes  // <-- DEVE ESTAR AQUI
    ]);
}


    public function artigo() {
    $slug = $_GET['slug'] ?? '';
    
    if(empty($slug)) {
        Store::redirect('blog');
        return;
    }
    
    $pubModel = new \core\models\Publicacoes();
    $artigo = $pubModel->buscarPorSlug($slug);
    
    if(!$artigo) {
        // Redirecionar para o blog se não encontrar
        Store::redirect('blog');
        return;
    }
    
    $config = new \core\models\Configuracao();
    
    Store::Layout(['layouts/html_header', 'layouts/header', 'artigo', 'layouts/footer', 'layouts/html_footer'], [
        'artigo' => $artigo,
        'config' => $config
    ]);
}

 public function contacto() {
    $config = new \core\models\Configuracao();
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $mensagem = $_POST['mensagem'] ?? '';
        $mailer = new EnviarEmail();
        // Usar email vindo da BD
        $para = $config->get('email_contacto', DS_EMAIL);
        if($mailer->enviar_contacto($nome, $email, $telefone, $mensagem, $para)) {
            $_SESSION['msg_sucesso'] = "Mensagem enviada com sucesso!";
        } else {
            $_SESSION['msg_erro'] = "Erro ao enviar. Tente mais tarde.";
        }
        Store::redirect('contacto');
    }
    Store::Layout(['layouts/html_header', 'layouts/header', 'contacto', 'layouts/footer', 'layouts/html_footer'], ['config' => $config]);
}



}