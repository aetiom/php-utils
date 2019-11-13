<?php

namespace Aetiom\PhpUtils;

/**
 * Session manager
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package PHP-Utils
 * @version 1.0
 */
class Session extends Asset {
    
    /**
     * @const int ERR_ARG_VALUE_NOT_NUMERIC :
     * Exception code for not numeric arguments
     */
    const ERR_ARG_VALUE_NOT_NUMERIC = 201;
    
    /**
     * @const int ERR_CONTENT_NOT_NUMERIC :
     * Exception code for not numeric asset content
     */
    const ERR_CONTENT_NOT_NUMERIC = 301;
    
    
    
    /**
     * Constructor
     * 
     * @param mixed $key : session key
     * @param array $root
     */
    public function __construct($key, &$root = array(), $keyTree = array()) 
    {
        if (empty($keyTree) && empty($root)) {
            $root = &$_SESSION;
        }
        
        parent::__construct($key, $root, $keyTree);
    }
    
    
    
    /**
     * Add value to actual content
     * @param mixed $value : numercic value
     */
    public function add($value)
    {
        $f_val = $this->formatNumeric($value);
        
        if (!is_numeric($this->asset)) {
            throw \Exception(implode(' / ', $this->keyTree)
                    .' does not have a numeric content', 
                    self::ERR_CONTENT_NOT_NUMERIC);
        }
        
        $this->asset += $f_val;
    }
    
    /**
     * Multiply actual content by value
     * @param mixed $value : numercic value
     */
    public function multiply($value)
    {
        $f_val = $this->formatNumeric($value);
        
        if (!is_numeric($this->asset)) {
            throw \Exception(implode(' / ', $this->keyTree)
                    .' does not have a numeric content', 
                    self::ERR_CONTENT_NOT_NUMERIC);
        }
        
        $this->asset *= $f_val;
    }
    
    /**
     * Multiply actual content by value
     * @param mixed $value : numercic value
     */
    public function modulus($value)
    {
        $f_val = $this->formatNumeric($value);
        
        if (!is_numeric($this->asset)) {
            throw \Exception(implode(' / ', $this->keyTree)
                    .' does not have a numeric content', 
                    self::ERR_CONTENT_NOT_NUMERIC);
        }
        
        $this->asset %= $f_val;
    }
    
    /**
     * Format numeric value
     * 
     * @param mixed $value : value to check and format
     * @return int|float formated value
     * 
     * @throws \Exception if value parameter is not numeric
     */
    private function formatNumeric($value)
    {
        if (!is_numeric($value)) {
            throw \Exception($value.' is not a numeric value. Numeric value needed'
                    .' for this operation on '.implode(' / ', $this->keyTree), 
                    self::ERR_ARG_VALUE_NOT_NUMERIC);
        }
        
        if (is_float($value)) {
            return (float)$value;
        }
        
        return (int)$value;
    }
}