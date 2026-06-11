<?php
/**
 * LocaleHelper - Deteta idioma e moeda baseado no país
 * 
 * @package SevenLux
 */

namespace core\classes;

class LocaleHelper
{
    /**
     * Mapeamento de países -> idioma (código ISO)
     */
    private static $countryToLanguage = [
        'portugal' => 'pt',
        'brasil' => 'pt-br',
        'angola' => 'pt',
        'moçambique' => 'pt',
        'cabo verde' => 'pt',
        'guiné-bissau' => 'pt',
        'são tomé' => 'pt',
        'timor' => 'pt',
        'espanha' => 'es',
        'espanha' => 'es',
        'frança' => 'fr',
        'reino unido' => 'en',
        'inglaterra' => 'en',
        'estados unidos' => 'en',
        'eua' => 'en',
        'alemanha' => 'de',
        'itália' => 'it',
        'holanda' => 'nl',
        'bélgica' => 'nl',
        'suíça' => 'de',
        'áustria' => 'de',
    ];
    
    /**
     * Mapeamento de países -> moeda
     */
    private static $countryToCurrency = [
        'portugal' => 'EUR',
        'brasil' => 'BRL',
        'angola' => 'AOA',
        'moçambique' => 'MZN',
        'cabo verde' => 'CVE',
        'espanha' => 'EUR',
        'frança' => 'EUR',
        'alemanha' => 'EUR',
        'itália' => 'EUR',
        'holanda' => 'EUR',
        'bélgica' => 'EUR',
        'irlanda' => 'EUR',
        'estados unidos' => 'USD',
        'reino unido' => 'GBP',
        'suíça' => 'CHF',
    ];
    
    /**
     * Símbolos das moedas
     */
    private static $currencySymbols = [
        'EUR' => '€',
        'USD' => '$',
        'BRL' => 'R$',
        'AOA' => 'Kz',
        'MZN' => 'MT',
        'CVE' => '​',
        'GBP' => '£',
        'CHF' => 'CHF',
    ];
    
    /**
     * Deteta o idioma baseado no país
     * 
     * @param string|null $pais
     * @return string Código do idioma (ex: 'pt', 'en', 'es')
     */
    public static function getLanguageFromCountry($pais = null)
    {
        if (empty($pais)) {
            return 'pt'; // default
        }
        
        $paisLower = strtolower(trim($pais));
        
        // Remove acentos para comparação
        $paisLower = iconv('utf-8', 'ascii//TRANSLIT', $paisLower);
        
        foreach (self::$countryToLanguage as $key => $lang) {
            if (strpos($paisLower, $key) !== false) {
                return $lang;
            }
        }
        
        return 'pt'; // fallback
    }
    
    /**
     * Deteta a moeda baseada no país
     * 
     * @param string|null $pais
     * @return string Código da moeda (ex: 'EUR', 'USD')
     */
    public static function getCurrencyFromCountry($pais = null)
    {
        if (empty($pais)) {
            return 'EUR'; // default
        }
        
        $paisLower = strtolower(trim($pais));
        $paisLower = iconv('utf-8', 'ascii//TRANSLIT', $paisLower);
        
        foreach (self::$countryToCurrency as $key => $currency) {
            if (strpos($paisLower, $key) !== false) {
                return $currency;
            }
        }
        
        return 'EUR'; // fallback
    }
    
    /**
     * Retorna o símbolo da moeda
     * 
     * @param string $currency Código da moeda
     * @return string
     */
    public static function getCurrencySymbol($currency)
    {
        return self::$currencySymbols[$currency] ?? $currency;
    }
    
    /**
     * Formata um valor com a moeda correta
     * 
     * @param float $valor
     * @param string $currency
     * @return string
     */
    public static function formatMoney($valor, $currency = 'EUR')
    {
        $symbol = self::getCurrencySymbol($currency);
        
        if ($currency === 'BRL') {
            return $symbol . ' ' . number_format($valor, 2, ',', '.');
        }
        
        return $symbol . ' ' . number_format($valor, 2, ',', '.');
    }
}