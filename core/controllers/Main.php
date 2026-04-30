<?php
namespace core\controllers;

use core\classes\Store;
use core\classes\EnviarEmail;
use core\models\Imovel;
use core\models\Publicacao;

class Main {
  public function index() {
    $config = new \core\models\Configuracao();
    $imovelModel = new Imovel();
    $pubModel = new Publicacao();
    $imoveis = $imovelModel->listar(true, 3);
    $publicacoes = $pubModel->listar(3);
    
    Store::Layout(['layouts/html_header', 'layouts/header', 'home', 'layouts/footer', 'layouts/html_footer'], [
        'imoveis' => $imoveis,
        'publicacoes' => $publicacoes,
        'config' => $config  // passar objeto de configuração
    ]);
}
    public function imovel() {
        $slug = $_GET['slug'] ?? '';
        $imovelModel = new Imovel();
        $imovel = $imovelModel->buscarPorSlug($slug);
        if(!$imovel) Store::redirect();
        Store::Layout(['layouts/html_header', 'layouts/header', 'imovel', 'layouts/footer', 'layouts/html_footer'], [
            'imovel' => $imovel
        ]);
    }

    public function blog() {
        $pubModel = new Publicacao();
        $publicacoes = $pubModel->listar();
        Store::Layout(['layouts/html_header', 'layouts/header', 'blog', 'layouts/footer', 'layouts/html_footer'], [
            'publicacoes' => $publicacoes
        ]);
    }

    public function artigo() {
        $slug = $_GET['slug'] ?? '';
        $pubModel = new Publicacao();
        $artigo = $pubModel->buscarPorSlug($slug);
        if(!$artigo) Store::redirect('blog');
        Store::Layout(['layouts/html_header', 'layouts/header', 'artigo', 'layouts/footer', 'layouts/html_footer'], [
            'artigo' => $artigo
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