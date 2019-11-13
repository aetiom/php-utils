<?php

namespace Aetiom\PhpUtils;

/**
 * Abstract options class
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package PHP-Utils
 * @version 1.0
 */
abstract class Options {
    
    /**
     * Constructor
     * @param array $param : optional parameters
     */
    public function __construct(array $param = []) 
    {
        if (empty($param)) {
            return;
        }
        
        $rc = new \ReflectionClass($this);
        foreach ($rc->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $name = $prop->name;
            if (isset($param[$name])) {
                $this->$name = $param[$name];
            }
        }
    }
}
