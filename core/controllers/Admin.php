<?php
/**
 * Admin Controller
 * 
 * Handles all back-office operations: dashboard, configurations, services,
 * gallery, products, blog posts. All methods require authentication and
 * enforce that the logged‑in user only accesses their own tenant data.
 * 
 * @package SevenLux
 */

namespace core\controllers;

use core\classes\Store;
use core\classes\Database;
use core\models\Configuracao;
use core\models\Clientes;
use core\models\Servicos;
use core\models\Galeria;
use core\models\Produtos;
use core\models\Publicacoes;

class Admin
{
    /**
     * Verifies that the user is logged in and that the requested slug
     * matches the session slug (prevents cross‑tenant access).
     */
    private function verificarLogin()
    {
        if (!isset($_SESSION['cliente_id'])) {
            Store::redirect('admin_login');
            exit;
        }

        // 🔐 Critical: ensure URL slug matches session slug
        if (defined('CLIENTE_SLUG') && CLIENTE_SLUG !== $_SESSION['cliente_slug']) {
            session_destroy();
            Store::redirect('admin_login');
            exit;
        }
    }

    /**
     * Shows the admin login form.
     * Redirects to admin dashboard if already logged in.
     */
    public function admin_login()
    {
        if (isset($_SESSION['cliente_id'])) {
            header("Location: " . BASE_URL . $_SESSION['cliente_slug'] . "/admin");
            exit;
        }
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/login_frm',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ]);
    }

    /**
     * Processes the login submission.
     * Validates slug + digit code, handles account blocking, and redirects accordingly.
     */
    public function admin_login_submit()
    {
        $slug = trim($_POST['text_slug'] ?? '');
        $digits = trim($_POST['text_digitos'] ?? '');

        error_log("LOGIN - Slug: $slug, Digits: $digits");

        if (empty($slug) || empty($digits)) {
            $_SESSION['erro'] = "Preencha o slug e o código de acesso.";
            header("Location: " . BASE_URL . "index.php?a=admin_login");
            exit;
        }

        $clientModel = new Clientes();
        $db = new Database();

        // Check account blocking
        $clientInfo = $db->select(
            "SELECT bloqueio_ate FROM sevenlux_clientes WHERE slug = :slug",
            [':slug' => $slug]
        );
        if ($clientInfo && $clientInfo[0]->bloqueio_ate > time()) {
            $remaining = $clientInfo[0]->bloqueio_ate - time();
            $_SESSION['erro'] = "Conta bloqueada. Tente novamente em $remaining segundos.";
            header("Location: " . BASE_URL . $slug . "/admin_login");
            exit;
        }

        $client = $clientModel->validar_login($slug, $digits);

        if ($client) {
            // Successful login
            session_regenerate_id(true);
            $_SESSION = [];
            $_SESSION['cliente_id'] = $client->id_cliente;
            $_SESSION['cliente_slug'] = $client->slug;
            $_SESSION['cliente_email'] = $client->email;
            header("Location: " . BASE_URL . $client->slug . "/admin");
            exit;
        } else {
            // Failed login – redirect to the same slug's login page
            $_SESSION['erro'] = "Slug ou código incorretos.";
            header("Location: " . BASE_URL . $slug . "/admin_login");
            exit;
        }
    }

    /**
     * Logs out the admin user and destroys the session.
     */
    public function admin_logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: " . BASE_URL . "index.php");
        exit;
    }

    /**
     * Admin dashboard – shows all configurable modules.
     */
    public function admin()
    {
        $this->verificarLogin();
        $config = new Configuracao();
        $services = (new Servicos())->listar();
        $gallery = (new Galeria())->listar();
        $products = (new Produtos())->listar();
        $posts = (new Publicacoes())->listar();

        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/dashboard',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], [
            'config' => $config,
            'servicos' => $services,
            'galeria' => $gallery,
            'produtos' => $products,
            'publicacoes' => $posts
        ]);
    }

    // ============================================================
    // CONFIGURATIONS
    // ============================================================

    /**
     * Displays the site configuration form.
     */
    public function admin_configuracoes()
    {
        $this->verificarLogin();
        $config = new Configuracao();
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/configuracoes',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['config' => $config]);
    }

    /**
     * Saves the site configuration (text fields and logo upload).
     */
    public function admin_salvar_config()
    {
        $this->verificarLogin();
        $config = new Configuracao();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fields = [
                'logo_parte1', 'logo_parte2', 'slogan', 'texto_descritivo',
                'meta_description', 'meta_keywords', 'email_contacto',
                'telefone', 'endereco'
            ];
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    $config->set($field, trim($_POST[$field]));
                }
            }

            // Logo image upload
            if (isset($_FILES['logo_imagem']) && $_FILES['logo_imagem']['error'] === 0) {
                $uploadDir = __DIR__ . '/../../public/assets/images/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['logo_imagem']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($ext, $allowedExtensions)) {
                    $fileName = time() . '_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['logo_imagem']['tmp_name'], $uploadDir . $fileName)) {
                        $config->set('logo_imagem', $fileName);
                    }
                }
            }

            $_SESSION['sucesso'] = "Configurações atualizadas!";
        }

        Store::redirect('admin_configuracoes');
    }

    // ============================================================
    // SERVICES (CRUD)
    // ============================================================

    public function admin_servicos()
    {
        $this->verificarLogin();
        $services = (new Servicos())->listar();
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/servicos',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['servicos' => $services]);
    }

    public function admin_servico_criar()
    {
        $this->verificarLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            (new Servicos())->criar($_POST);
            $_SESSION['sucesso'] = "Serviço criado!";
            Store::redirect('admin_servicos');
        }
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/servico_form',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ]);
    }

    public function admin_servico_editar()
    {
        $this->verificarLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new Servicos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->atualizar($id, $_POST);
            $_SESSION['sucesso'] = "Serviço atualizado!";
            Store::redirect('admin_servicos');
        }

        $service = $model->buscar($id);
        if (!$service) {
            Store::redirect('admin_servicos');
        }
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/servico_form',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['servico' => $service]);
    }

    public function admin_servico_deletar()
    {
        $this->verificarLogin();
        $id = (int) ($_POST['id'] ?? 0);
        (new Servicos())->deletar($id);
        echo json_encode(['success' => true]);
    }

    // ============================================================
    // GALLERY (CRUD)
    // ============================================================

    public function admin_galeria()
    {
        $this->verificarLogin();
        $gallery = (new Galeria())->listar();
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/galeria',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['galeria' => $gallery]);
    }

    public function admin_galeria_criar()
    {
        $this->verificarLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagem'])) {
            $model = new Galeria();

            if ($model->contar() >= 7) {
                $_SESSION['erro'] = "Máximo de 7 fotos!";
                Store::redirect('admin_galeria');
                return;
            }

            $uploadDir = __DIR__ . '/../../public/assets/images/galeria/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowedExtensions)) {
                $_SESSION['erro'] = "Formato inválido!";
                Store::redirect('admin_galeria');
                return;
            }

            $fileName = time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $fileName)) {
                $model->criar($fileName, $_POST['legenda'] ?? null);
                $_SESSION['sucesso'] = "Imagem adicionada!";
            } else {
                $_SESSION['erro'] = "Erro ao salvar imagem.";
            }
        }

        Store::redirect('admin_galeria');
    }

    public function admin_galeria_deletar()
    {
        $this->verificarLogin();
        $id = (int) ($_POST['id'] ?? 0);
        (new Galeria())->deletar($id);
        echo json_encode(['success' => true]);
    }

    // ============================================================
    // PRODUCTS (CRUD)
    // ============================================================

    public function admin_produtos()
    {
        $this->verificarLogin();
        $products = (new Produtos())->listar();
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/produtos',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['produtos' => $products]);
    }

    public function admin_produto_criar()
    {
        $this->verificarLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Produtos();

            if ($model->contar() >= 7) {
                $_SESSION['erro'] = "Máximo de 6 produtos!";
                Store::redirect('admin_produtos');
                return;
            }

            $image = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $uploadDir = __DIR__ . '/../../public/assets/images/produtos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $image = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $image);
            }

            $model->criar($_POST, $image);
            $_SESSION['sucesso'] = "Produto criado!";
            Store::redirect('admin_produtos');
        }

        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/produto_form',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ]);
    }

    public function admin_produto_editar()
    {
        $this->verificarLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new Produtos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $uploadDir = __DIR__ . '/../../public/assets/images/produtos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $image = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $image);
            }
            $model->atualizar($id, $_POST, $image);
            $_SESSION['sucesso'] = "Produto atualizado!";
            Store::redirect('admin_produtos');
        }

        $product = $model->buscar($id);
        if (!$product) {
            Store::redirect('admin_produtos');
        }
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/produto_form',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['produto' => $product]);
    }

    public function admin_produto_deletar()
    {
        $this->verificarLogin();
        $id = (int) ($_POST['id'] ?? 0);
        (new Produtos())->deletar($id);
        echo json_encode(['success' => true]);
    }

    // ============================================================
    // BLOG POSTS (CRUD)
    // ============================================================

    public function admin_publicacoes()
    {
        $this->verificarLogin();
        $posts = (new Publicacoes())->listar();
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/publicacoes',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['publicacoes' => $posts]);
    }

    public function admin_publicacao_criar()
    {
        $this->verificarLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Publicacoes();

            if ($model->contar() >= 7) {
                $_SESSION['erro'] = "Máximo de 7 publicações!";
                Store::redirect('admin_publicacoes');
                return;
            }

            $image = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $uploadDir = __DIR__ . '/../../public/assets/images/blog/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $image = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $image);
            }

            $model->criar($_POST, $image);
            $_SESSION['sucesso'] = "Publicação criada!";
            Store::redirect('admin_publicacoes');
        }

        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/publicacao_form',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ]);
    }

    public function admin_publicacao_editar()
    {
        $this->verificarLogin();
        $id = (int) ($_GET['id'] ?? 0);
        $model = new Publicacoes();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = null;
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                $uploadDir = __DIR__ . '/../../public/assets/images/blog/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                $image = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadDir . $image);
            }
            $model->atualizar($id, $_POST, $image);
            $_SESSION['sucesso'] = "Publicação atualizada!";
            Store::redirect('admin_publicacoes');
        }

        $post = $model->buscar($id);
        if (!$post) {
            Store::redirect('admin_publicacoes');
        }
        Store::Layout([
            'admin/layouts/html_header',
            'admin/layouts/header',
            'admin/publicacao_form',
            'admin/layouts/footer',
            'admin/layouts/html_footer'
        ], ['publicacao' => $post]);
    }

    public function admin_publicacao_deletar()
    {
        $this->verificarLogin();
        $id = (int) ($_POST['id'] ?? 0);
        (new Publicacoes())->deletar($id);
        echo json_encode(['success' => true]);
    }
}