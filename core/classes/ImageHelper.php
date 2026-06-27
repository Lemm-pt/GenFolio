<?php
/**
 * ImageHelper - Compressão e otimização de imagens
 * Versão compatível com todos os servidores
 * 
 * @package SevenLux
 */

namespace core\classes;

use Exception;

class ImageHelper
{
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    const QUALITY = 80;
    
    /**
     * Processa uma imagem (usa o método mais simples e compatível)
     */
    public static function processarImagem($file, $tipo, $uploadDir)
    {
        // Verificar erro no upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            error_log("Erro no upload: " . $file['error']);
            return false;
        }
        
        // Verificar tamanho
        if ($file['size'] > self::MAX_FILE_SIZE) {
            error_log("Ficheiro muito grande: " . $file['size'] . " bytes");
            return false;
        }
        
        // Criar diretório
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Método mais compatível: usar o GD se disponível
        $filename = null;
        
        if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
            $filename = self::processarComGD($file, $uploadDir);
        }
        
        // Fallback: copiar o ficheiro original
        if (!$filename) {
            $filename = self::processarSemGD($file, $uploadDir);
        }
        
        return $filename;
    }
    
    /**
     * Processar usando GD (compatível com todos os hosts)
     */
    private static function processarComGD($file, $uploadDir)
    {
        try {
            // Ler o conteúdo da imagem
            $imageData = file_get_contents($file['tmp_name']);
            if (!$imageData) {
                return false;
            }
            
            // Criar imagem a partir da string (função mais compatível)
            $image = @imagecreatefromstring($imageData);
            if (!$image) {
                return false;
            }
            
            // Obter dimensões
            $width = imagesx($image);
            $height = imagesy($image);
            
            // Redimensionar (max 1200px)
            $maxSize = 1200;
            if ($width > $maxSize || $height > $maxSize) {
                $ratio = $width / $height;
                if ($width > $height) {
                    $newWidth = $maxSize;
                    $newHeight = $maxSize / $ratio;
                } else {
                    $newHeight = $maxSize;
                    $newWidth = $maxSize * $ratio;
                }
                
                $newImage = imagecreatetruecolor($newWidth, $newHeight);
                
                // Manter transparência para PNG
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                
                imagecopyresampled(
                    $newImage, $image,
                    0, 0, 0, 0,
                    $newWidth, $newHeight,
                    $width, $height
                );
                
                imagedestroy($image);
                $image = $newImage;
            }
            
            // Gerar nome do ficheiro
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $filepath = $uploadDir . $filename;
            
            // Guardar a imagem
            $success = false;
            $mime = mime_content_type($file['tmp_name']);
            
            if ($mime === 'image/png') {
                $success = imagepng($image, $filepath, 6);
            } elseif ($mime === 'image/gif') {
                $success = imagegif($image, $filepath);
            } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
                $success = imagewebp($image, $filepath, self::QUALITY);
            } else {
                // JPEG por defeito
                $success = imagejpeg($image, $filepath, self::QUALITY);
            }
            
            imagedestroy($image);
            
            if ($success) {
                error_log("✅ Imagem processada com GD: $filename");
                return $filename;
            }
            
        } catch (Exception $e) {
            error_log("❌ Erro no processamento GD: " . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Processar sem GD - apenas copiar o ficheiro
     */
    private static function processarSemGD($file, $uploadDir)
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            error_log("✅ Imagem copiada sem compressão: $filename");
            return $filename;
        }
        
        error_log("❌ Falha ao copiar imagem");
        return false;
    }
    
    /**
     * Validar imagem
     */
    public static function validarImagem($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['valid' => false, 'error' => 'Erro no upload da imagem'];
        }
        
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return ['valid' => false, 'error' => 'Imagem demasiado pesada. Máximo: 5MB'];
        }
        
        $imageInfo = @getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            return ['valid' => false, 'error' => 'Ficheiro não é uma imagem válida'];
        }
        
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($imageInfo['mime'], $allowedTypes)) {
            return ['valid' => false, 'error' => 'Formato não suportado. Use: JPG, PNG, GIF ou WEBP'];
        }
        
        return ['valid' => true, 'error' => null];
    }
}