<?php
/**
 * Store Utility Class
 * 
 * Provides core helper methods for layout rendering, redirection,
 * session checks, hash generation, and URL handling for the multi-tenant system.
 * 
 * @package SevenLux
 */

namespace core\classes;

use Exception;

class Store
{
    /**
     * Renders a set of layout views.
     *
     * @param array $structures List of view file paths (relative to core/views/)
     * @param array|null $data   Associative array of variables to extract into the view scope.
     * @throws Exception If $structures is not an array.
     */
    public static function Layout($structures, $data = null)
    {
        if (!is_array($structures)) {
            throw new Exception("Coleção de estruturas inválida");
        }
        if (!empty($data) && is_array($data)) {
            extract($data);
        }
        foreach ($structures as $structure) {
            include("../core/views/$structure.php");
        }
    }

    /**
     * Redirects to a given route while preserving the client slug in the URL.
     *
     * @param string $route The route name (e.g., 'admin', 'blog', or empty for home).
     */
    public static function redirect($route = '')
    {
        // Get slug from session or fallback to demo
        $slug = $_SESSION['cliente_slug'] ?? 'vitrine-demo';

        // Routes that must use the plain index.php (no friendly URL)
       $simpleRoutes = [
           'admin_login', 'admin_login_submit', 'admin_logout',
           'criar_cliente', 'confirmar_email',
           'recuperar_codigo', 'recuperar_codigo_submit',
           'recuperar_codigo_confirmar', 'recuperar_codigo_novo_submit'
         ];

        if (in_array($route, $simpleRoutes)) {
            header("Location: " . BASE_URL . "index.php?a=" . $route);
            exit;
        }

        $url = BASE_URL . $slug . '/';
        if (!empty($route) && $route !== 'inicio') {
            $url .= $route;
        }
        header("Location: " . $url);
        exit;
    }

    /**
     * Checks whether the current admin user is logged in.
     *
     * @return bool
     */
    public static function adminLogado()
    {
        return isset($_SESSION['cliente_id']);
    }

    /**
     * Generates a random hash with the specified number of characters.
     *
     * @param int $length Number of characters (default: 12).
     * @return string
     */
    public static function criarHash($length = 12)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $length);
    }

    /**
     * Returns the current client slug.
     * Priority: session (logged-in admin) > CLIENTE_SLUG constant > fallback 'vitrine-demo'.
     *
     * @return string
     */
    public static function getClienteSlug()
    {
        if (isset($_SESSION['cliente_slug'])) {
            return $_SESSION['cliente_slug'];
        }
        if (defined('CLIENTE_SLUG')) {
            return CLIENTE_SLUG;
        }
        return 'vitrine-demo';
    }

    /**
     * Returns the base URL for the current client (including trailing slash).
     *
     * @return string
     */
    public static function getBaseUrl()
    {
        if (isset($_SESSION['cliente_slug'])) {
            return BASE_URL . $_SESSION['cliente_slug'] . '/';
        }
        // Fallback to the constant defined in index.php
        return BASE_URL . CLIENTE_SLUG . '/';
    }

    /**
     * Generates a full URL for a given route, respecting client slug and special simple routes.
     * 
     * @param string $route The route name (e.g., 'blog', 'admin', 'artigo').
     * @return string
     * 
     * @todo The 'artigo' route only returns the base blog URL without the article slug.
     *       For now, this matches the original behaviour, but may need correction later.
     */
    public static function getUrl($route = '')
    {
        $slug = self::getClienteSlug();
        $simpleRoutes = [
            'novo_cliente', 'criar_cliente', 'confirmar_email',
            'recuperar_password', 'admin_login'
        ];

        if (in_array($route, $simpleRoutes)) {
            return BASE_URL . 'index.php?a=' . $route;
        }

        // Special case for blog article – returns only the article listing URL
        if ($route === 'artigo') {
            return BASE_URL . $slug . '/artigo';
        }

        return BASE_URL . $slug . '/' . $route;
    }

    /**
     * Returns the current client slug (alias of getClienteSlug).
     * 
     * @deprecated Use self::getClienteSlug() instead.
     * @return string
     */
    public static function getCurrentSlug()
    {
        return self::getClienteSlug();
    }
}