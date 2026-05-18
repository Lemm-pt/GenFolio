<?php
namespace core\controllers;

use core\classes\Store;
use core\models\Clientes;
use core\models\Configuracao;
use core\models\Servicos;
use core\models\Galeria;
use core\models\Produtos;
use core\models\Publicacoes;

class Admin {
    
    private function verificarLogin() {
        if(!isset($_SESSION['cliente_id'])) {
            Store::redirect('admin_login');
            exit;
        }
    }
    
   public function admin_login() {
    // Se já está logado, vai para o admin
    if(isset($_SESSION['cliente_id'])) {
        header("Location: " . BASE_URL . $_SESSION['cliente_slug'] . "/admin");
        exit;
    }
    Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/login_frm', 'admin/layouts/footer', 'admin/layouts/html_footer']);
}

public function admin_login_submit() {
    $email = trim($_POST['text_admin'] ?? '');
    $senha = trim($_POST['text_senha'] ?? '');
    
    $clienteModel = new \core\models\Clientes();
    $cliente = $clienteModel->validar_login($email, $senha);
    
    if($cliente) {
        session_regenerate_id(true);
        $_SESSION = []; // Limpa completamente a sessão
        $_SESSION['cliente_id'] = $cliente->id_cliente;
        $_SESSION['cliente_slug'] = $cliente->slug;
        $_SESSION['cliente_email'] = $cliente->email;
        
        // Redirecionar para o admin do cliente correto
        header("Location: " . BASE_URL . $cliente->slug . "/admin");
        exit;
    } else {
        $_SESSION['erro'] = "Email ou senha inválidos.";
        header("Location: " . BASE_URL . "index.php?a=admin_login");
        exit;
    }
}

public function admin_logout() {
    $_SESSION = [];
    session_destroy();
    header("Location: " . BASE_URL . "index.php");
    exit;
}
    
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
    // CONFIGURAÇÕES
    // ============================================================
    public function admin_configuracoes() {
        $this->verificarLogin();
        $config = new Configuracao();
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/configuracoes', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['config' => $config]);
    }
    
    public function admin_salvar_config() {
        $this->verificarLogin();
        $config = new Configuracao();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

        
            $campos = ['logo_parte1', 'logo_parte2', 'slogan', 'texto_descritivo', 'meta_description', 'meta_keywords', 'email_contacto', 'telefone', 'endereco'];
            foreach($campos as $campo) {
                if(isset($_POST[$campo])) $config->set($campo, trim($_POST[$campo]));
            }
            $_SESSION['sucesso'] = "Configurações atualizadas!";
        }

        // Dentro do método admin_salvar_config, antes do redirect
       // Dentro de admin_salvar_config, antes do redirect
if(isset($_FILES['logo_imagem']) && $_FILES['logo_imagem']['error'] === 0) {
    $upload_dir = __DIR__ . '/../../public/assets/images/';
    if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $ext = strtolower(pathinfo($_FILES['logo_imagem']['name'], PATHINFO_EXTENSION));
    $extensoes_validas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if(in_array($ext, $extensoes_validas)) {
        $nome_arquivo = time() . '_' . uniqid() . '.' . $ext;
        if(move_uploaded_file($_FILES['logo_imagem']['tmp_name'], $upload_dir . $nome_arquivo)) {
            $config->set('logo_imagem', $nome_arquivo);
        }
    }
}
        Store::redirect('admin_configuracoes');
    }
    
    // ============================================================
    // SERVIÇOS (CRUD)
    // ============================================================
    public function admin_servicos() {
        $this->verificarLogin();
        $servicos = (new Servicos())->listar();
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/servicos', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['servicos' => $servicos]);
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
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/servico_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['servico' => $servico]);
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
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/galeria', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['galeria' => $galeria]);
    }
    
    public function admin_galeria_criar() {
        $this->verificarLogin();
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
            $model = new Galeria();
            if($model->contar() >= 7) {
                $_SESSION['erro'] = "Máximo de 7 fotos!";
                Store::redirect('admin_galeria');
                return;
            }
            $upload_dir = __DIR__ . '/../../public/assets/images/galeria/';
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $extensoes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if(!in_array($ext, $extensoes)) {
                $_SESSION['erro'] = "Formato inválido!";
                Store::redirect('admin_galeria');
                return;
            }
            $nome = time() . '_' . uniqid() . '.' . $ext;
            if(move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_dir . $nome)) {
                $model->criar($nome, $_POST['legenda'] ?? null);
                $_SESSION['sucesso'] = "Imagem adicionada!";
            } else {
                $_SESSION['erro'] = "Erro ao salvar imagem.";
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
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/produtos', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['produtos' => $produtos]);
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
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/produto_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['produto' => $produto]);
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
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/publicacoes', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['publicacoes' => $publicacoes]);
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
        Store::Layout(['admin/layouts/html_header', 'admin/layouts/header', 'admin/publicacao_form', 'admin/layouts/footer', 'admin/layouts/html_footer'], ['publicacao' => $publicacao]);
    }
    
    public function admin_publicacao_deletar() {
        $this->verificarLogin();
        $id = $_POST['id'] ?? 0;
        (new Publicacoes())->deletar($id);
        echo json_encode(['success' => true]);
    }
}