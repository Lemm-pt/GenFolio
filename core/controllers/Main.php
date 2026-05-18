<?php
namespace core\controllers;

use core\classes\Store;
use core\classes\EnviarEmail;
use core\models\Clientes;
use core\models\Configuracao;
use core\models\Servicos;
use core\models\Galeria;
use core\models\Produtos;
use core\models\Publicacoes;

class Main {
    
    // ============================================================
    // PÁGINA INICIAL
    // ============================================================
 public function index() {
    $cliente_id = CLIENTE_ID; // constante definida no index.php
    $config = new Configuracao($cliente_id);
    $servicos = (new Servicos())->listar();
    $galeria = (new Galeria())->listar();
    $produtos = (new Produtos())->listar(6);
    $publicacoes = (new Publicacoes())->listar(3);
    
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
        'publicacoes' => $publicacoes,
        'slug_atual' => CLIENTE_SLUG
    ]);
}
    
    // ============================================================
    // BLOG
    // ============================================================
    public function blog() {
        $publicacoes = (new Publicacoes())->listar();
        $config = new Configuracao();
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'blog',
            'layouts/footer',
            'layouts/html_footer'
        ], ['publicacoes' => $publicacoes, 'config' => $config]);
    }
    


public function artigo() {
    $slug = $_GET['slug'] ?? '';
    
    if(empty($slug)) {
        Store::redirect('blog');
        return;
    }
    
    $publicacoesModel = new \core\models\Publicacoes();
    $artigo = $publicacoesModel->buscarPorSlug($slug);
    
    if(!$artigo) {
        Store::redirect('blog');
        return;
    }
    
    // DEBUG - verificar se o artigo foi encontrado
    error_log("Artigo encontrado: " . $artigo->titulo);
    
    $config = new \core\models\Configuracao();
    
    Store::Layout([
        'layouts/html_header',
        'layouts/header',
        'artigo',
        'layouts/footer',
        'layouts/html_footer'
    ], [
        'artigo' => $artigo,   // <-- NOME DA VARIÁVEL NA VIEW
        'config' => $config
    ]);
}
    // ============================================================
    // CONTACTO
    // ============================================================
    public function contacto() {
        $config = new Configuracao();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefone = $_POST['telefone'] ?? '';
            $mensagem = $_POST['mensagem'] ?? '';
            $mailer = new EnviarEmail();
            $para = $config->get('email_contacto', DS_EMAIL);
            
            if($mailer->enviar_contacto($nome, $email, $telefone, $mensagem, $para)) {
                $_SESSION['msg_sucesso'] = "Mensagem enviada com sucesso!";
            } else {
                $_SESSION['msg_erro'] = "Erro ao enviar. Tente mais tarde.";
            }
            Store::redirect('contacto');
        }
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'contacto',
            'layouts/footer',
            'layouts/html_footer'
        ], ['config' => $config]);
    }
    
 // ============================================================
// REGISTO DE NOVO CLIENTE (formulário)
// ============================================================
public function novo_cliente() {
    $config = new Configuracao(); // para o layout
    Store::Layout([
        'layouts/html_header',
        'layouts/header',
        'criar_cliente',
        'layouts/footer',
        'layouts/html_footer'
    ], ['config' => $config]);
}

// ============================================================
// PROCESSAR REGISTO
// ============================================================
public function criar_cliente() {
    // Se não for POST, redireciona para o formulário (usando URL absoluta para evitar slug)
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        header("Location: " . BASE_URL . "index.php?a=novo_cliente");
        exit;
    }
    
    $email = trim($_POST['text_email'] ?? '');
    $slug = $this->gerarSlug($_POST['text_slug'] ?? '');
    $senha = $_POST['text_senha_1'] ?? '';
    $confirmar_senha = $_POST['text_senha_2'] ?? '';
    
    // Validações
    $erros = [];
    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Email inválido.';
    }
    if(empty($slug)) {
        $erros[] = 'O nome do site (slug) é obrigatório.';
    }
    if(strlen($senha) < 6) {
        $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
    }
    if($senha !== $confirmar_senha) {
        $erros[] = 'As senhas não coincidem.';
    }
    
    if(!empty($erros)) {
        $_SESSION['erro'] = implode(' ', $erros);
        header("Location: " . BASE_URL . "index.php?a=novo_cliente");
        exit;
    }
    
    $clienteModel = new Clientes();
    
    if($clienteModel->verificar_email_existe($email)) {
        $_SESSION['erro'] = 'Este email já está registado.';
        header("Location: " . BASE_URL . "index.php?a=novo_cliente");
        exit;
    }
    
    if($clienteModel->verificar_slug_existe($slug)) {
        $_SESSION['erro'] = 'Este nome de site já está a ser utilizado. Escolha outro.';
        header("Location: " . BASE_URL . "index.php?a=novo_cliente");
        exit;
    }
    
    $purl = $clienteModel->registar_cliente($email, $slug, $senha);
    
    if($purl) {
        // Enviar email (opcional)
        $emailObj = new EnviarEmail();
        $emailObj->enviar_confirmacao_registo($email, $purl, $slug);
        
        $_SESSION['email_temporario'] = $email;
        
        // Apresentar página de sucesso (sem redirecionamento, usando a mesma URL base)
        $config = new Configuracao();
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'criar_cliente_sucesso',
            'layouts/footer',
            'layouts/html_footer'
        ], ['config' => $config]);
    } else {
        $_SESSION['erro'] = 'Erro ao registar. Tente novamente.';
        header("Location: " . BASE_URL . "index.php?a=novo_cliente");
        exit;
    }
}

// ============================================================
// CONFIRMAR EMAIL (link recebido por email)
// ============================================================
public function confirmar_email() {
    $purl = $_GET['purl'] ?? '';
    if(empty($purl)) Store::redirect('inicio');
    
    $clienteModel = new \core\models\Clientes();
    $cliente = $clienteModel->buscarPorPurl($purl);
    
    if($cliente && $clienteModel->confirmar_email($purl)) {
        // Mensagem de sucesso
        $_SESSION['sucesso_login'] = "✅ Conta confirmada com sucesso! Faça login para aceder ao seu site: " . $cliente->slug;
        
        // Redirecionar para o login (usando index.php para evitar problemas)
        header("Location: " . BASE_URL . "index.php?a=admin_login");
        exit;
    } else {
        $_SESSION['erro'] = 'Link de confirmação inválido ou expirado.';
        Store::redirect('inicio');
    }
}

// ============================================================
// GERAR SLUG (texto amigável para URL)
// ============================================================
private function gerarSlug($texto) {
    $texto = preg_replace('~[^\pL\d]+~u', '-', $texto);
    $texto = iconv('utf-8', 'us-ascii//TRANSLIT', $texto);
    $texto = preg_replace('~[^-\w]+~', '', $texto);
    $texto = trim($texto, '-');
    $texto = strtolower($texto);
    if(empty($texto)) $texto = 'cliente-' . time();
    return $texto;
}
    // ============================================================
    // LOGIN/LOGOUT
    // ============================================================
    public function login() {
        if(Store::adminLogado()) Store::redirect('admin');
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'login_frm',
            'layouts/footer',
            'layouts/html_footer'
        ]);
    }
    
     public function login_submit() {
           if(Store::adminLogado()) Store::redirect('admin');
           if($_SERVER['REQUEST_METHOD'] != 'POST') Store::redirect('login');
           
           $slug = trim($_POST['text_usuario'] ?? '');
           $senha = trim($_POST['text_senha'] ?? '');
           
           $clienteModel = new Clientes();
           $cliente = $clienteModel->validar_login($slug, $senha);
           
           if($cliente) {
               $_SESSION['cliente_id'] = $cliente->id_cliente;
               $_SESSION['cliente_slug'] = $cliente->slug;
               Store::redirect('admin');
           } else {
               $_SESSION['erro'] = "Login inválido. Verifique o nome do seu site (slug) e senha.";
               Store::redirect('login');
           }
       }
    
  public function logout() {
    session_destroy();
    header("Location: " . BASE_URL . "index.php");
    exit;
}
    
    // ============================================================
    // RECUPERAÇÃO DE PASSWORD
    // ============================================================
    public function recuperar_password() {
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'recuperar_password',
            'layouts/footer',
            'layouts/html_footer'
        ]);
    }
    
    public function recuperar_password_submit() {
        if($_SERVER['REQUEST_METHOD'] != 'POST') Store::redirect('recuperar_password');
        
        $email = trim($_POST['text_email'] ?? '');
        $clienteModel = new Clientes();
        
        if(!$clienteModel->verificar_email_existe($email)) {
            $_SESSION['erro'] = "Email não encontrado.";
            Store::redirect('recuperar_password');
            return;
        }
        
        $token = $clienteModel->gerarTokenRecuperacao($email);
        $emailObj = new EnviarEmail();
        $emailObj->enviar_recuperacao_password($email, $token);
        
        $_SESSION['sucesso'] = "Enviamos um email com as instruções para recuperar a sua password.";
        Store::redirect('recuperar_password');
    }
    
    public function recuperar_password_confirmar() {
        $token = $_GET['token'] ?? '';
        $clienteModel = new Clientes();
        $cliente = $clienteModel->validarTokenRecuperacao($token);
        
        if(!$cliente) {
            $_SESSION['erro'] = "Link inválido ou expirado.";
            Store::redirect('recuperar_password');
            return;
        }
        
        $_SESSION['reset_token'] = $token;
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'nova_password',
            'layouts/footer',
            'layouts/html_footer'
        ]);
    }
    
    public function nova_password_submit() {
        $token = $_SESSION['reset_token'] ?? '';
        $nova_senha = $_POST['text_nova_senha'] ?? '';
        $confirmar_senha = $_POST['text_confirmar_senha'] ?? '';
        
        if(empty($token)) {
            Store::redirect('recuperar_password');
            return;
        }
        
        if(strlen($nova_senha) < 6) {
            $_SESSION['erro'] = "A senha deve ter pelo menos 6 caracteres.";
            Store::redirect('recuperar_password_confirmar&token=' . $token);
            return;
        }
        
        if($nova_senha !== $confirmar_senha) {
            $_SESSION['erro'] = "As senhas não coincidem.";
            Store::redirect('recuperar_password_confirmar&token=' . $token);
            return;
        }
        
        $clienteModel = new Clientes();
        $cliente = $clienteModel->validarTokenRecuperacao($token);
        
        if(!$cliente) {
            $_SESSION['erro'] = "Link inválido ou expirado.";
            Store::redirect('recuperar_password');
            return;
        }
        
        $clienteModel->atualizarPassword($cliente->id_cliente, $nova_senha);
        unset($_SESSION['reset_token']);
        
        $_SESSION['sucesso'] = "Password alterada com sucesso! Já pode fazer login.";
        Store::redirect('login');
    }
    
     public function conta_inativa() {
            $slug = $_SESSION['cliente_inactivo'] ?? '';
            Store::Layout([
                'layouts/html_header',
                'layouts/header',
                'conta_inativa',
                'layouts/footer',
                'layouts/html_footer'
            ], ['slug' => $slug]);
      }





}