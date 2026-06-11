<?php
/**
 * Main Controller
 * 
 * Handles public pages: home, blog, contact, registration, login (client area),
 * password recovery, and code recovery for admin access.
 * 
 * @package SevenLux
 */

namespace core\controllers;

use core\classes\Store;
use core\classes\EnviarEmail;
use core\models\Clientes;
use core\models\Configuracao;
use core\models\Servicos;
use core\models\Galeria;
use core\models\Produtos;
use core\models\Publicacoes;

class Main
{
    // ============================================================
    // HOME PAGE
    // ============================================================
    
    /**
     * Displays the home page with services, products, gallery, and recent blog posts.
     */
    public function index()
    {
        $clientId = CLIENTE_ID; // defined in index.php
        $config = new Configuracao($clientId);
        $services = (new Servicos())->listar();
        $gallery = (new Galeria())->listar();
        $products = (new Produtos())->listar(6);
        $posts = (new Publicacoes())->listar(3);
        
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'home',
            'layouts/footer',
            'layouts/html_footer'
        ], [
            'config'      => $config,
            'servicos'    => $services,
            'galeria'     => $gallery,
            'produtos'    => $products,
            'publicacoes' => $posts,
            'slug_atual'  => CLIENTE_SLUG
        ]);
    }
    
    // ============================================================
    // BLOG
    // ============================================================
    
    /**
     * Lists all blog posts.
     */
    public function blog()
    {
        $posts = (new Publicacoes())->listar();
        $config = new Configuracao();
        
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'blog',
            'layouts/footer',
            'layouts/html_footer'
        ], [
            'publicacoes' => $posts,
            'config'      => $config
        ]);
    }
    
    /**
     * Displays a single blog article.
     */
    public function artigo()
    {
        // Article slug comes from $_GET['slug_artigo'] (set by .htaccess)
        $articleSlug = $_GET['slug_artigo'] ?? '';
        
        if (empty($articleSlug)) {
            Store::redirect('blog');
            return;
        }
        
        $postsModel = new Publicacoes(CLIENTE_ID);
        $article = $postsModel->buscarPorSlug($articleSlug);
        
        if (!$article) {
            Store::redirect('blog');
            return;
        }
        
        $config = new Configuracao(CLIENTE_ID);
        
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'artigo',
            'layouts/footer',
            'layouts/html_footer'
        ], [
            'artigo' => $article,
            'config' => $config
        ]);
    }
    
    // ============================================================
    // CONTACT FORM
    // ============================================================
    
    /**
     * Displays the contact form and processes submissions.
     */
    public function contacto()
    {
        $config = new Configuracao();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = $_POST['nome'] ?? '';
            $email    = $_POST['email'] ?? '';
            $phone    = $_POST['telefone'] ?? '';
            $message  = $_POST['mensagem'] ?? '';
            $mailer   = new EnviarEmail();
            $recipient = $config->get('email_contacto', EMAIL_FROM);
            
            if ($mailer->enviar_contacto($name, $email, $phone, $message, $recipient)) {
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
    // CLIENT REGISTRATION
    // ============================================================
    
    /**
     * Shows the registration form for a new client (store owner).
     */
    public function novo_cliente()
    {
        $config = new Configuracao();
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'criar_cliente',
            'layouts/footer',
            'layouts/html_footer'
        ], ['config' => $config]);
    }
    
    /**
     * Processes the registration form submission.
     */
    public function criar_cliente()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "index.php?a=novo_cliente");
            exit;
        }
        
        $email      = trim($_POST['text_email'] ?? '');
        $slug       = $this->gerarSlug($_POST['text_slug'] ?? '');
        $digits     = $_POST['text_digitos'] ?? '';
        $questionId = (int) ($_POST['pergunta_id'] ?? 0);
        $answerId   = (int) ($_POST['resposta_id'] ?? 0);
        $cidade     = trim($_POST['text_cidade'] ?? '');
        $pais       = trim($_POST['text_pais'] ?? '');
        $categoria  = trim($_POST['text_categoria'] ?? '');
        
        // Validation
        $errors = [];
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }
        if (empty($slug)) {
            $errors[] = 'O nome do site (slug) é obrigatório.';
        }
        if (strlen($digits) < 1 || strlen($digits) > 7 || !ctype_digit($digits)) {
            $errors[] = 'O código deve ter entre 1 e 7 dígitos.';
        }
        if ($questionId < 1 || $answerId < 1) {
            $errors[] = 'Selecione a pergunta e a resposta.';
        }
        
        if (!empty($errors)) {
            $_SESSION['erro'] = implode(' ', $errors);
            header("Location: " . BASE_URL . "index.php?a=novo_cliente");
            exit;
        }
        
        $clientModel = new Clientes();
        
        if ($clientModel->verificar_email_existe($email)) {
            $_SESSION['erro'] = 'Este email já está registado.';
            header("Location: " . BASE_URL . "index.php?a=novo_cliente");
            exit;
        }
        
        if ($clientModel->verificar_slug_existe($slug)) {
            $_SESSION['erro'] = 'Este nome de site já está a ser utilizado. Escolha outro.';
            header("Location: " . BASE_URL . "index.php?a=novo_cliente");
            exit;
        }
        
        $purl = $clientModel->registar_cliente($email, $slug, $digits, $questionId, $answerId, $cidade, $pais, $categoria);

   
        
        if ($purl) {
            $emailer = new EnviarEmail();
            $emailer->enviar_confirmacao_registo($email, $purl, $slug);
            $_SESSION['email_temporario'] = $email;
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
    
    /**
     * Confirms a client's email address via the link sent after registration.
     */
    public function confirmar_email()
    {
        $purl = $_GET['purl'] ?? '';
        if (empty($purl)) {
            Store::redirect('inicio');
        }
        
        $clientModel = new Clientes();
        $client = $clientModel->buscarPorPurl($purl);
        
        if ($client && $clientModel->confirmar_email($purl)) {
    // Guarda o slug temporariamente para usar no redirecionamento
    $slugCliente = $client->slug;
    
    // Mensagem de sucesso
    $_SESSION['sucesso'] = "✅ Conta confirmada com sucesso! Agora faça login com o seu código de acesso.";
    
    // Redireciona diretamente para o login DAQUELE cliente (com slug bonito)
    header("Location: " . BASE_URL . $slugCliente . "/admin_login");
    exit;
        } else {
            $_SESSION['erro'] = 'Link de confirmação inválido ou expirado.';
            Store::redirect('inicio');
        }
    }
    
    /**
     * Generates a URL-friendly slug from a given string.
     *
     * @param string $text
     * @return string
     */
    private function gerarSlug($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        if (empty($text)) {
            $text = 'cliente-' . time();
        }
        return $text;
    }
    
    // ============================================================
    // CLIENT LOGIN (front‑end area)
    // ============================================================
    
    /**
     * Displays the login form for the client area (distinct from admin login).
     * Note: Admin login is handled by Admin controller.
     */
    public function login()
    {
        if (Store::adminLogado()) {
            Store::redirect('admin');
        }
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'login_frm',
            'layouts/footer',
            'layouts/html_footer'
        ]);
    }
    
    /**
     * Processes the client login submission.
     */
    public function login_submit()
    {
        if (Store::adminLogado()) {
            Store::redirect('admin');
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Store::redirect('login');
        }
        
        $slug   = trim($_POST['text_slug'] ?? '');
        $digits = trim($_POST['text_digitos'] ?? '');
        
        if (empty($slug) || empty($digits)) {
            $_SESSION['erro'] = "Preencha o slug e o código de acesso.";
            Store::redirect('login');
            return;
        }
        
        $clientModel = new Clientes();
        $client = $clientModel->validar_login($slug, $digits);
        
        if ($client) {
            $_SESSION['cliente_id']   = $client->id_cliente;
            $_SESSION['cliente_slug'] = $client->slug;
            Store::redirect('admin');
        } else {
            $_SESSION['erro'] = "Slug ou código incorretos.";
            Store::redirect('login');
        }
    }
    
    /**
     * Logs out the current client (destroys session and redirects to home).
     */
    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . "index.php");
        exit;
    }
    
    // ============================================================
    // PASSWORD RECOVERY (email‑based)
    // ============================================================
    
    public function recuperar_password()
    {
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'recuperar_password',
            'layouts/footer',
            'layouts/html_footer'
        ]);
    }
    
    public function recuperar_password_submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Store::redirect('recuperar_password');
        }
        
        $email = trim($_POST['text_email'] ?? '');
        $clientModel = new Clientes();
        
        if (!$clientModel->verificar_email_existe($email)) {
            $_SESSION['erro'] = "Email não encontrado.";
            Store::redirect('recuperar_password');
            return;
        }
        
        $token = $clientModel->gerarTokenRecuperacao($email);
        $mailer = new EnviarEmail();
        $mailer->enviar_recuperacao_password($email, $token);
        
        $_SESSION['sucesso'] = "Enviamos um email com as instruções para recuperar a sua password.";
        Store::redirect('recuperar_password');
    }
    
    public function recuperar_password_confirmar()
    {
        $token = $_GET['token'] ?? '';
        $clientModel = new Clientes();
        $client = $clientModel->validarTokenRecuperacao($token);
        
        if (!$client) {
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
    
    public function nova_password_submit()
    {
        $token = $_SESSION['reset_token'] ?? '';
        $newPassword = $_POST['text_nova_senha'] ?? '';
        $confirmPassword = $_POST['text_confirmar_senha'] ?? '';
        
        if (empty($token)) {
            Store::redirect('recuperar_password');
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $_SESSION['erro'] = "A senha deve ter pelo menos 6 caracteres.";
            Store::redirect('recuperar_password_confirmar&token=' . $token);
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['erro'] = "As senhas não coincidem.";
            Store::redirect('recuperar_password_confirmar&token=' . $token);
            return;
        }
        
        $clientModel = new Clientes();
        $client = $clientModel->validarTokenRecuperacao($token);
        
        if (!$client) {
            $_SESSION['erro'] = "Link inválido ou expirado.";
            Store::redirect('recuperar_password');
            return;
        }
        
        $clientModel->atualizarPassword($client->id_cliente, $newPassword);
        unset($_SESSION['reset_token']);
        
        $_SESSION['sucesso'] = "Password alterada com sucesso! Já pode fazer login.";
        Store::redirect('login');
    }
    
    // ============================================================
    // INACTIVE ACCOUNT PAGE (unused? kept for compatibility)
    // ============================================================
    
    public function conta_inativa()
    {
        $slug = $_SESSION['cliente_inactivo'] ?? '';
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'conta_inativa',
            'layouts/footer',
            'layouts/html_footer'
        ], ['slug' => $slug]);
    }
    
    // ============================================================
    // DIGIT CODE RECOVERY (security question based)
    // ============================================================
    
    /**
     * Shows the form to recover a lost digit code using a security question.
     */
    public function recuperar_codigo()
    {
        $config = new Configuracao();
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'recuperar_codigo',
            'layouts/footer',
            'layouts/html_footer'
        ], ['config' => $config]);
    }
    
    /**
     * Processes the code recovery submission.
     */
    public function recuperar_codigo_submit()
    {
        $slug          = $_POST['text_slug'] ?? '';
        $answerId      = (int) ($_POST['resposta_id'] ?? 0);
        $newDigits     = $_POST['novos_digitos'] ?? '';
        
        $clientModel = new Clientes();
        if ($clientModel->recuperarCodigo($slug, $answerId, $newDigits)) {
            $_SESSION['sucesso'] = "Código redefinido com sucesso! Use o novo código para entrar.";
        } else {
            $_SESSION['erro'] = "Falha na recuperação. Verifique o slug, a resposta ou o novo código.";
        }
        Store::redirect('recuperar_codigo');
    }
    
    /**
     * AJAX endpoint to fetch the security question for a given slug.
     */
    public function ajax_get_pergunta()
    {
        $slug = $_GET['slug'] ?? '';
        if (empty($slug)) {
            echo json_encode(['pergunta' => null]);
            exit;
        }
        
        $clientModel = new Clientes();
        $question = $clientModel->getPerguntaBySlug($slug);
        
        header('Content-Type: application/json');
        
        if ($question) {
            echo json_encode([
                'pergunta'  => $question['texto'],
                'respostas' => $question['respostas']
            ]);
        } else {
            echo json_encode(['pergunta' => null]);
        }
        exit;
    }
}