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

         // 🔥 RATE LIMIT
        $rateLimiter = new \core\classes\RateLimiter();
        
        if (!$rateLimiter->podeRealizar('contacto')) {
            $_SESSION['msg_erro'] = "Demasiadas mensagens. Tente novamente mais tarde.";
            Store::redirect('contacto');
            return;
        }


               // Honeypot anti-spam
           if (!empty($_POST['empresa_interna_777'])) {
          
              error_log(
                  "BOT DETETADO | IP: " .
                  ($_SERVER['REMOTE_ADDR'] ?? 'desconhecido')
              );
          
              exit;
          }

            //Humano → 20 segundos, 30 segundos, 1 minuto. BOT = 0.1 morre logo
            $tempo = time() - ($_SESSION['contact_form_time'] ?? 0);

               if ($tempo < 5) {
                   exit;
             }

            $name     = $_POST['nome'] ?? '';
            $email    = $_POST['email'] ?? '';
            $phone    = $_POST['telefone'] ?? '';
            $message  = $_POST['mensagem'] ?? '';
            $mailer   = new EnviarEmail();
            $recipient = $config->get('email_contacto', EMAIL_FROM);
            
            if ($mailer->enviar_contacto($name, $email, $phone, $message, $recipient)) {

                $rateLimiter->reset('contacto');
                $_SESSION['msg_sucesso'] = "Mensagem enviada com sucesso!";
            } else {
                 $rateLimiter->registrarTentativa('contacto');
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

         // 🔥 RATE LIMIT - Verificar antes de qualquer coisa
        $rateLimiter = new \core\classes\RateLimiter();
    
        if (!$rateLimiter->podeRealizar('registro')) {
             $tempoRestante = $rateLimiter->getTempoRestante('registro');
             $_SESSION['erro'] = "Demasiadas tentativas de registo. Tente novamente em " . ceil($tempoRestante / 60) . " minutos.";
             header("Location: " . BASE_URL . "index.php?a=novo_cliente");
             exit;
    }
        
        $email      = trim($_POST['text_email'] ?? '');
        $slug       = $this->gerarSlug($_POST['text_slug'] ?? '');
        $digits     = $_POST['text_digitos'] ?? '';
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
        
      $token = $clientModel->registar_cliente($email, $slug, $digits, $cidade, $pais, $categoria);

        if ($token) {

         // 🔥 SUCESSO - Resetar tentativas (ou manter, mas já não importa)
        $rateLimiter->reset('registro');

         // 🔥 LOG: Novo cliente registado
           \core\classes\Logger::log('registro_cliente', "Novo cliente registado: $slug (Email: $email)");

            $emailer = new EnviarEmail();
            $emailer->enviar_confirmacao_registo($email, $token, $slug);
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

        // 🔥 FALHA - Registar tentativa
        $rateLimiter->registrarTentativa('registro');
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
    $token = $_GET['token'] ?? '';
    if (empty($token)) {
        Store::redirect('inicio');
    }
    
    $clientModel = new Clientes();
    $client = $clientModel->buscarPorTokenConfirmacao($token);
    
    if ($client && $clientModel->confirmar_email($token)) {

       // 🔥 LOG: Email confirmado
        \core\classes\Logger::log('confirmar_email', "Email confirmado para: " . $client->slug, $client->id_cliente);

        $_SESSION['sucesso'] = "✅ Conta confirmada com sucesso! Agora faça login com o seu código de acesso.";
        header("Location: " . BASE_URL . $client->slug . "/admin_login");
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
             session_regenerate_id(true);
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
    
/**
 * Shows the form to recover a lost digit code via email.
 */
public function recuperar_codigo()
{
    // Tentar obter slug da URL (GET) ou da sessão
    $slug = $_GET['slug'] ?? $_SESSION['cliente_slug'] ?? '';
    
    // Se não houver slug, redirecionar para a página inicial do demo
    if (empty($slug)) {
        header("Location: " . BASE_URL . "vitrine-demo/");
        exit;
    }
    
    // 🔥 Buscar o cliente_id a partir do slug
    $db = new \core\classes\Database();
    $cliente = $db->select(
        "SELECT id_cliente FROM sevenlux_clientes WHERE slug = :slug AND activo = 1",
        [':slug' => $slug]
    );
    
    $clienteId = ($cliente && !empty($cliente)) ? (int)$cliente[0]->id_cliente : 1;
    
    // 🔥 Passar o cliente_id para o Configuracao
    $config = new Configuracao($clienteId, true);
    
    // 🔥 Guardar o slug na sessão para usar depois
    $_SESSION['recovery_slug'] = $slug;
    
    Store::Layout([
        'layouts/html_header',
        'layouts/header',
        'recuperar_codigo',
        'layouts/footer',
        'layouts/html_footer'
    ], [
        'config' => $config,
        'recovery_slug' => $slug
    ]);
}
    
public function recuperar_codigo_submit()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Store::redirect('recuperar_codigo');
        return;
    }

    // 🔥 RATE LIMIT
    $rateLimiter = new \core\classes\RateLimiter();
    
    if (!$rateLimiter->podeRealizar('recuperacao')) {
        $tempoRestante = $rateLimiter->getTempoRestante('recuperacao');
        $_SESSION['erro'] = "Demasiados pedidos. Tente novamente em " . ceil($tempoRestante / 60) . " minutos.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo&slug=" . urlencode($_POST['text_slug']));
        exit;
    }
    
    $slug = trim($_POST['text_slug'] ?? '');
    
    if (empty($slug)) {
        $_SESSION['erro'] = "Por favor, insira o slug do seu site.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo");
        exit;
    }
    
    $clientModel = new Clientes();
    $result = $clientModel->gerarTokenRecuperacaoCodigo($slug);
    
    // Verificar se o resultado é um array e tem as chaves necessárias
    if (is_array($result) && isset($result['email']) && isset($result['token'])) {
        // Enviar email com link de recuperação
        $mailer = new EnviarEmail();
        $emailEnviado = $mailer->enviar_recuperacao_codigo($result['email'], $result['token'], $slug);
        
        if ($emailEnviado) {
            // 🔥 SUCESSO - Resetar tentativas
            $rateLimiter->reset('recuperacao');

            // 🔥 LOG: Pedido de recuperação de código
            \core\classes\Logger::log('recuperar_codigo', "Pedido de recuperação de código para slug: $slug");

            $_SESSION['sucesso'] = "✅ Enviamos um email com as instruções para recuperar o seu código de acesso.";
        } else {
            $_SESSION['erro'] = "❌ Erro ao enviar email. Tente novamente mais tarde.";
        }
    } else {
        // 🔥 Slug inválido - registar tentativa
        $rateLimiter->registrarTentativa('recuperacao');
        // Não revelar se o slug existe ou não (segurança)
        $_SESSION['sucesso'] = "✅ Se o slug existir, enviamos um email com as instruções para recuperar o código de acesso.";
    }
    
    header("Location: " . BASE_URL . "index.php?a=recuperar_codigo&slug=" . urlencode($slug));
    exit;
}

public function recuperar_codigo_confirmar()
{
    $token = $_GET['token'] ?? '';
    $slug = $_GET['slug'] ?? '';

    if (empty($token)) {
        $_SESSION['erro'] = "Link inválido.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo");
        exit;
    }

    $clientModel = new Clientes();
    $client = $clientModel->validarTokenRecuperacaoCodigo($token);

    if (!$client) {
        $_SESSION['erro'] = "Link inválido ou expirado.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo");
        exit;
    }

    // 🔥 Verificar se o slug da URL corresponde ao slug do cliente
    if (!empty($slug) && $slug !== $client->slug) {
        $_SESSION['erro'] = "Slug inválido.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo");
        exit;
    }

    $_SESSION['recovery_token'] = $token;
    $_SESSION['recovery_slug'] = $client->slug;

    // 🔥 CORRIGIR: Usar o caminho correto para o action do formulário
    $actionUrl = BASE_URL . 'index.php?a=recuperar_codigo_novo_submit&slug=' . urlencode($client->slug);
    $loginUrl = BASE_URL . $client->slug . '/admin_login';

    ?>
    <!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redefinir Código</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body { background: #0a0a1a; color: #eee; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding-top: 80px; }
            .card { background: #1e1e2e; border-radius: 15px; border: none; }
            .card-header { background: #C6A43F; color: #0a0a1a; border-radius: 15px 15px 0 0 !important; padding: 20px; }
            .btn-gold { background: #C6A43F; color: #0a0a1a; border: none; padding: 12px; border-radius: 50px; font-weight: bold; width: 100%; }
            .btn-gold:hover { background: #d4b96a; }
            .code-display { font-family: monospace; font-size: 28px; letter-spacing: 8px; text-align: center; background: #0a0a1a; padding: 15px; border-radius: 10px; border: 2px solid #C6A43F; }
            .numpad-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-width: 280px; margin: 0 auto; }
            .numpad-btn { padding: 15px; font-size: 22px; font-weight: bold; background: #2a2a35; color: white; border: none; border-radius: 12px; cursor: pointer; transition: all 0.1s; }
            .numpad-btn:active { transform: scale(0.95); background: #C6A43F; color: #0a0a1a; }
            .alert-danger { background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #ff6b6b; border-radius: 10px; padding: 10px; margin-bottom: 15px; }
            .form-label { margin-bottom: 10px; font-weight: 500; }
            .text-muted { color: #888 !important; }
            .text-gold { color: #C6A43F !important; text-decoration: none; }
            .text-gold:hover { color: #d4b96a !important; text-decoration: underline; }
        </style>
    </head>
    <body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3><i class="fas fa-lock"></i> Criar Novo Código</h3>
                        <p class="mb-0">Defina o seu novo código de acesso (1 a 7 dígitos)</p>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['erro'])): ?>
                            <div class="alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= $actionUrl ?>" method="POST" id="formNovoCodigo">
                            <div class="mb-4">
                                <label class="form-label">Novo código (1 a 7 dígitos) *</label>
                                <input type="hidden" name="novos_digitos" id="novos_digitos" value="">

                                <div class="code-display text-center mb-4" id="codeDisplay">▪ ▪ ▪ ▪ ▪ ▪ ▪</div>

                                <div class="numpad-grid" id="numpadNovoCodigo"></div>
                                <small class="text-muted d-block text-center mt-3">Clique nos botões para definir o seu novo código secreto.</small>
                            </div>

                            <button type="submit" class="btn-gold">
                                <i class="fas fa-save"></i> Redefinir Código
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="<?= $loginUrl ?>" class="text-gold">
                                <i class="fas fa-arrow-left"></i> Voltar ao login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        let digits = "";
        const MAX_DIGITS = 7;

        const inputHidden = document.getElementById('novos_digitos');
        const codeDisplay = document.getElementById('codeDisplay');
        const container = document.getElementById('numpadNovoCodigo');

        if (!container) return;

        function atualizarDisplay() {
            let masked = '';
            for (let i = 0; i < digits.length; i++) masked += '● ';
            for (let i = digits.length; i < MAX_DIGITS; i++) masked += '▪ ';
            codeDisplay.innerText = masked.trim();
            if (inputHidden) inputHidden.value = digits;
        }

        function adicionarDigito(num) {
            if (digits.length < MAX_DIGITS) {
                digits += num.toString();
                atualizarDisplay();
            }
        }

        function resetDigitos() { digits = ""; atualizarDisplay(); }
        function apagarDigito() { digits = digits.slice(0, -1); atualizarDisplay(); }

        container.innerHTML = '';

        for (let i = 1; i <= 9; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = 'numpad-btn';
            btn.type = 'button';
            btn.onclick = (function(num) { return function() { adicionarDigito(num); }; })(i);
            container.appendChild(btn);
        }

        const btnZero = document.createElement('button');
        btnZero.textContent = '0';
        btnZero.className = 'numpad-btn';
        btnZero.type = 'button';
        btnZero.onclick = () => adicionarDigito(0);
        container.appendChild(btnZero);

        const btnReset = document.createElement('button');
        btnReset.textContent = 'Reset';
        btnReset.className = 'numpad-btn';
        btnReset.type = 'button';
        btnReset.onclick = resetDigitos;
        container.appendChild(btnReset);

        const btnApagar = document.createElement('button');
        btnApagar.textContent = '⌫';
        btnApagar.className = 'numpad-btn';
        btnApagar.type = 'button';
        btnApagar.onclick = apagarDigito;
        container.appendChild(btnApagar);

        atualizarDisplay();

        const form = document.getElementById('formNovoCodigo');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (digits.length === 0) {
                    e.preventDefault();
                    alert('❌ Por favor, defina o novo código de acesso (1-7 dígitos)!');
                }
            });
        }
    })();
    </script>
    </body>
    </html>
    <?php
    exit;
}

public function recuperar_codigo_novo_submit()
{
    $token = $_SESSION['recovery_token'] ?? '';
    $newDigits = $_POST['novos_digitos'] ?? '';
    $slug = $_GET['slug'] ?? $_SESSION['recovery_slug'] ?? '';

    if (empty($token)) {
        $_SESSION['erro'] = "Sessão inválida.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo");
        exit;
    }

    if (empty($slug)) {
        $_SESSION['erro'] = "Slug inválido.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo");
        exit;
    }

    if (strlen($newDigits) < 1 || strlen($newDigits) > 7 || !ctype_digit($newDigits)) {
        $_SESSION['erro'] = "O código deve ter entre 1 e 7 dígitos.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo_confirmar&token=" . urlencode($token) . "&slug=" . urlencode($slug));
        exit;
    }

    $clientModel = new Clientes();
    $result = $clientModel->redefinirCodigoPorToken($token, $newDigits);

    if ($result) {
        unset($_SESSION['recovery_token']);
        unset($_SESSION['recovery_slug']);
        $_SESSION['sucesso'] = "✅ Código redefinido com sucesso!";
        // 🔥 Redirecionar para o login do cliente correto
        header("Location: " . BASE_URL . $slug . "/admin_login");
        exit;
    } else {
        $_SESSION['erro'] = "❌ Erro ao redefinir código. Tente novamente.";
        header("Location: " . BASE_URL . "index.php?a=recuperar_codigo_confirmar&token=" . urlencode($token) . "&slug=" . urlencode($slug));
        exit;
    }
}



/**
 * Exibe a Política de Privacidade
 */
public function politica_privacidade()
{
    $config = new Configuracao(CLIENTE_ID);
    
    Store::Layout([
        'layouts/html_header',
        'layouts/header',
        'politica_privacidade',
        'layouts/footer',
        'layouts/html_footer'
    ], [
        'config' => $config,
        'slug' => CLIENTE_SLUG
    ]);
}


/**
 * Página de manutenção
 */
public function manutencao()
{
    $status = $_GET['status'] ?? 'pausada';
    $tempo = $_GET['tempo'] ?? null;
    
    include('../core/views/manutencao.php');
    exit;
}
  

    
}