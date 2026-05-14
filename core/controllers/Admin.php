<?php
namespace core\controllers;

use core\classes\Store;
use core\models\Admin as AdminModel;
use core\models\Configuracao;
use core\models\Servicos;
use core\models\Galeria;
use core\models\Produtos;
use core\models\Publicacoes;

class Admin {
    
    private function verificarLogin() {
        if(!Store::adminLogado()) {
            Store::redirect('admin_login');
            exit;
        }
    }
    
    // ============================================================
    // LOGIN/LOGOUT
    // ============================================================
    public function admin_login() {
        if(Store::adminLogado()) {
            Store::redirect('admin');
            return;
        }
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
    
    // ============================================================
    // DASHBOARD PRINCIPAL
    // ============================================================
    public function admin() {
        $this->verificarLogin();
        $config = new Configuracao();
        $servicos = (new Servicos())->listar();
        $galeria = (new Galeria())->listar();
        $produtos = (new Produtos())->listar();
        $publicacoes = (new Publicacoes())->listar();
        
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/dashboard', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'config' => $config,
            'servicos' => $servicos,
            'galeria' => $galeria,
            'produtos' => $produtos,
            'publicacoes' => $publicacoes
        ]);
    }
    
    // ============================================================
    // CONFIGURAÇÕES GERAIS
    // ============================================================
    public function admin_configuracoes() {
        $this->verificarLogin();
        $config = new Configuracao();
        
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/configuracoes', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'config' => $config
        ]);
    }
    
   public function admin_salvar_config() {
    $this->verificarLogin();
    $config = new Configuracao();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $campos = ['logo_parte1', 'logo_parte2', 'slogan', 'texto_descritivo', 'meta_description', 'meta_keywords', 'email_contacto', 'telefone', 'endereco'];
        foreach($campos as $campo) {
            if(isset($_POST[$campo])) {
                $config->set($campo, trim($_POST[$campo]));
            }
        }
        $_SESSION['sucesso'] = "Configurações atualizadas!";
    }
    Store::redirect('admin_configuracoes');
  }
    
    // ============================================================
    // SERVIÇOS (CRUD)
    // ============================================================
    public function admin_servicos() {
        $this->verificarLogin();
        $servicos = (new Servicos())->listar();
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/servicos', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'servicos' => $servicos
        ]);
    }
    
    public function admin_servico_criar() {
        $this->verificarLogin();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Servicos())->criar($_POST);
            $_SESSION['sucesso'] = "Serviço criado!";
            Store::redirect('admin_servicos');
        }
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/servico_form', 'admin/layouts/footer', 'admin/layouts/html_footer']);
    }
    
    public function admin_servico_editar() {
        $this->verificarLogin();
        $id = $_GET['id'] ?? 0;
        $model = new Servicos();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->atualizar($id, $_POST);
            $_SESSION['sucesso'] = "Serviço atualizado!";
            Store::redirect('admin_servicos');
        }
        
        $servico = $model->buscar($id);
        if(!$servico) Store::redirect('admin_servicos');
        
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/servico_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'servico' => $servico
        ]);
    }
    
    public function admin_servico_deletar() {
        $this->verificarLogin();
        $id = $_POST['id'] ?? 0;
        (new Servicos())->deletar($id);
        echo json_encode(['success' => true]);
    }
    
    // ============================================================
    // GALERIA (CRUD)
    // ============================================================
    public function admin_galeria() {
        $this->verificarLogin();
        $galeria = (new Galeria())->listar();
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/galeria', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'galeria' => $galeria
        ]);
    }
    
 public function admin_galeria_criar() {
    $this->verificarLogin();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
        $galeria = new Galeria();
        
        if($galeria->contar() >= 7) {
            $_SESSION['erro'] = "Máximo de 7 fotos na galeria!";
            Store::redirect('admin_galeria');
            return;
        }
        
        if($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['erro'] = "Erro no upload da imagem.";
            Store::redirect('admin_galeria');
            return;
        }
        
        $upload_dir = __DIR__ . '/../../public/assets/images/galeria/';
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if(!in_array($ext, $extensoes_validas)) {
            $_SESSION['erro'] = "Formato inválido. Use JPG, PNG, GIF ou WEBP.";
            Store::redirect('admin_galeria');
            return;
        }
        
        $nome_arquivo = time() . '_' . uniqid() . '.' . $ext;
        
        if(move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_dir . $nome_arquivo)) {
            $galeria->criar($nome_arquivo, $_POST['legenda'] ?? null);
            $_SESSION['sucesso'] = "Imagem adicionada!";
        } else {
            $_SESSION['erro'] = "Erro ao salvar a imagem.";
        }
    }
    Store::redirect('admin_galeria');
}



    
    public function admin_galeria_deletar() {
        $this->verificarLogin();
        $id = $_POST['id'] ?? 0;
        (new Galeria())->deletar($id);
        echo json_encode(['success' => true]);
    }
    
    // ============================================================
    // PRODUTOS (CRUD)
    // ============================================================
    public function admin_produtos() {
        $this->verificarLogin();
        $produtos = (new Produtos())->listar();
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/produtos', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'produtos' => $produtos
        ]);
    }
    
    public function admin_produto_criar() {
        $this->verificarLogin();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Produtos();
            
            if($model->contar() >= 6) {
                $_SESSION['erro'] = "Máximo de 6 produtos!";
                Store::redirect('admin_produtos');
                return;
            }
            
            $imagem = null;
            if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $upload_dir = __DIR__ . '/../../public/assets/images/produtos/';
                if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $imagem = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_dir . $imagem);
            }
            
            $model->criar($_POST, $imagem);
            $_SESSION['sucesso'] = "Produto criado!";
            Store::redirect('admin_produtos');
        }
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/produto_form', 'admin/layouts/footer', 'admin/layouts/html_footer']);
    }
    
    public function admin_produto_editar() {
        $this->verificarLogin();
        $id = $_GET['id'] ?? 0;
        $model = new Produtos();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagem = null;
            if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $upload_dir = __DIR__ . '/../../public/assets/images/produtos/';
                if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $imagem = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_dir . $imagem);
            }
            $model->atualizar($id, $_POST, $imagem);
            $_SESSION['sucesso'] = "Produto atualizado!";
            Store::redirect('admin_produtos');
        }
        
        $produto = $model->buscar($id);
        if(!$produto) Store::redirect('admin_produtos');
        
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/produto_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'produto' => $produto
        ]);
    }
    
    public function admin_produto_deletar() {
        $this->verificarLogin();
        $id = $_POST['id'] ?? 0;
        (new Produtos())->deletar($id);
        echo json_encode(['success' => true]);
    }
    
    // ============================================================
    // PUBLICAÇÕES (CRUD)
    // ============================================================
    public function admin_publicacoes() {
        $this->verificarLogin();
        $publicacoes = (new Publicacoes())->listar();
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/publicacoes', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'publicacoes' => $publicacoes
        ]);
    }
    
    public function admin_publicacao_criar() {
        $this->verificarLogin();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Publicacoes();
            
            if($model->contar() >= 7) {
                $_SESSION['erro'] = "Máximo de 7 publicações!";
                Store::redirect('admin_publicacoes');
                return;
            }
            
            $imagem = null;
            if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $upload_dir = __DIR__ . '/../../public/assets/images/blog/';
                if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $imagem = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_dir . $imagem);
            }
            
            $model->criar($_POST, $imagem);
            $_SESSION['sucesso'] = "Publicação criada!";
            Store::redirect('admin_publicacoes');
        }
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/publicacao_form', 'admin/layouts/footer', 'admin/layouts/html_footer']);
    }
    
    public function admin_publicacao_editar() {
        $this->verificarLogin();
        $id = $_GET['id'] ?? 0;
        $model = new Publicacoes();
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagem = null;
            if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $upload_dir = __DIR__ . '/../../public/assets/images/blog/';
                if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $imagem = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_dir . $imagem);
            }
            $model->atualizar($id, $_POST, $imagem);
            $_SESSION['sucesso'] = "Publicação atualizada!";
            Store::redirect('admin_publicacoes');
        }
        
        $publicacao = $model->buscar($id);
        if(!$publicacao) Store::redirect('admin_publicacoes');
        
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/publicacao_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], [
            'publicacao' => $publicacao
        ]);
    }
    
    public function admin_publicacao_deletar() {
        $this->verificarLogin();
        $id = $_POST['id'] ?? 0;
        (new Publicacoes())->deletar($id);
        echo json_encode(['success' => true]);
    }
}