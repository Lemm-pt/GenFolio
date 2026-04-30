<?php
namespace core\controllers;

use core\classes\Store;
use core\models\Admin as AdminModel;
use core\models\Imovel;
use core\models\Publicacao;

class Admin {
    private function verificarLogin() {
        if(!Store::adminLogado()) {
            Store::redirect('admin_login');
            exit;
        }
    }

    public function admin_login() {
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/login_frm', 'admin/layouts/footer', 'admin/layouts/html_footer']);
    }

    public function admin_login_submit() {
        $username = $_POST['text_admin'] ?? '';
        $password = $_POST['text_senha'] ?? '';
        $model = new AdminModel();
        $admin = $model->validar($username, $password);
        if($admin) {
            $_SESSION['admin_id'] = $admin->id;
            $_SESSION['admin_user'] = $admin->username;
            Store::redirect('admin');
        } else {
            $_SESSION['erro'] = "Login inválido";
            Store::redirect('admin_login');
        }
    }

    public function admin_logout() {
        unset($_SESSION['admin_id'], $_SESSION['admin_user']);
        Store::redirect('admin_login');
    }

    public function admin() {
        $this->verificarLogin();
        $imovelModel = new Imovel();
        $pubModel = new Publicacao();
        $imoveis = $imovelModel->listar();
        $publicacoes = $pubModel->listar();
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/dashboard', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'imoveis' => $imoveis,
            'publicacoes' => $publicacoes
        ]);
    }

    // Imóveis
    public function admin_imovel_criar() {
        $this->verificarLogin();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Imovel();
            $model->criar($_POST);
            $_SESSION['sucesso'] = "Imóvel criado!";
            Store::redirect('admin');
        }
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/imovel_form', 'admin/layouts/footer', 'admin/layouts/html_footer']);
    }

    public function admin_imovel_editar() {
        $this->verificarLogin();
        $id = $_GET['id'] ?? 0;
        $model = new Imovel();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->atualizar($id, $_POST);
            $_SESSION['sucesso'] = "Imóvel atualizado!";
            Store::redirect('admin');
        }
        $imovel = $model->buscarPorSlug(''); // Não temos buscar por id, vamos adicionar um método
        // Melhor: adicionar método buscarPorId no model Imovel. Por simplificação, farei direto.
        $bd = new \core\classes\Database();
        $res = $bd->select("SELECT * FROM imoveis WHERE id = :id", [':id' => $id]);
        $imovel = $res ? $res[0] : null;
        if(!$imovel) Store::redirect('admin');
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/imovel_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['imovel' => $imovel]);
    }

    public function admin_imovel_deletar() {
        $this->verificarLogin();
        $id = $_POST['id'] ?? 0;
        $model = new Imovel();
        $model->deletar($id);
        echo json_encode(['success' => true]);
    }

    // Publicações
    public function admin_publicacao_criar() {
        $this->verificarLogin();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Publicacao();
            $model->criar($_POST);
            $_SESSION['sucesso'] = "Publicação criada!";
            Store::redirect('admin');
        }
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/publicacao_form', 'admin/layouts/footer', 'admin/layouts/html_footer']);
    }

    public function admin_publicacao_editar() {
        $this->verificarLogin();
        $id = $_GET['id'] ?? 0;
        $model = new Publicacao();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->atualizar($id, $_POST);
            $_SESSION['sucesso'] = "Publicação atualizada!";
            Store::redirect('admin');
        }
        $bd = new \core\classes\Database();
        $res = $bd->select("SELECT * FROM publicacoes WHERE id = :id", [':id' => $id]);
        $pub = $res ? $res[0] : null;
        if(!$pub) Store::redirect('admin');
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/publicacao_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['publicacao' => $pub]);
    }

    public function admin_publicacao_deletar() {
        $this->verificarLogin();
        $id = $_POST['id'] ?? 0;
        $model = new Publicacao();
        $model->deletar($id);
        echo json_encode(['success' => true]);
    }

public function admin_configuracoes() {
    $this->verificarLogin();
    $config = new \core\models\Configuracao();
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach($_POST as $chave => $valor) {
            if(in_array($chave, ['email_contacto', 'telefone_contacto', 'morada', 'logo_parte1', 'logo_parte2', 'nome_site', 'facebook_url', 'instagram_url', 'linkedin_url', 'tipo_servico'])) {
                $config->set($chave, trim($valor));
            }
        }
        $_SESSION['sucesso'] = "Configurações atualizadas!";
        Store::redirect('admin_configuracoes');
    }
    $todas = $config->getAll();
    Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/configuracoes', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['config' => $todas]);
}



}