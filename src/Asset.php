<?php

namespace Aetiom\PhpUtils;

/**
 * Standard recursive class
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package PHP-Utils
 * @version 1.0
 */
class Asset {
    
    /**
     * @const int ERR_ASSET_KEY_UNKNOWN :
     * Exception code for unknown asset key
     */
    const ERR_ASSET_KEY_UNKNOWN = 101;
    
    
    
    /**
     * @var mixed : actual asset reference
     */
    protected $asset = null;
    
    /**
     * @var mixed : actual asset key
     */
    protected $key = null;
    
    /**
     * @var array : tree view of previous and actual key used
     */
    protected $keyTree = [];
    
    /**
     * @var array : root asset reference 
     */
    protected $root = [];



    public function getAsset()
    {
        return $this->asset;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getKeyTree()
    {
        return $this->keyTree;
    }

    public function getRoot()
    {
        return $this->root;
    }

    

    
    /**
     * Constructor
     * 
     * @param mixed $key : session key
     * @param array $root
     */
    public function __construct($key, &$root = array(), $keyTree = array()) 
    {
        $this->keyTree = $keyTree;
        $this->root = &$root;
        
        $this->keyTree[] = $key;
        $this->key = $key;
        
        if (!isset($root[$key])) {
            $root[$key] = null;
        }
        
        $this->asset = &$root[$key];
    }
    
    
    
    /**
     * Select next level
     * 
     * @param mixed $subKey : next level key
     * @return mixed
     */
    public function select($subKey) 
    {
        return new static($subKey, $this->asset, $this->keyTree);
    }
    
    /**
     * Insert data into this session level
     * @param array $data : data to insert containing subKey as key and value
     */
    public function insert(Array $data)
    {
        foreach ($data as $subKey => $val) {
            if (isset($this->asset[$subKey])) {
                continue;
            }
            
            $this->asset[$subKey] = $val;
        }
    }
    
    /**
     * Update data into this session level
     * @param mixed $data : data to import, can be an array with subKey as key 
     *                      and value or simple value
     */
    public function update($data)
    {
        $this->asset = $data;
    }
    
    /**
     * Fetch data
     * 
     * @param mixed $subKey : data subKey to extract (can be null)
     * @return mixed : extracted data, can be an array
     * 
     * @throws \Exception if subKey does not exist
     */
    public function fetch($subKey = null)
    {
        /*
        var_dump($subKey);
        var_dump($this->asset);
        var_dump($this->asset[$subKey]);
        exit;
        */

        if ($subKey === null) {
            return $this->asset;
        }
        
        if (!isset($this->asset[$subKey])) {
            throw new \Exception(implode(' / ', $this->keyTree)
                    .' key '.$subKey.' does not exist', 
                    self::ERR_ASSET_KEY_UNKNOWN);
        }
        
        return $this->asset[$subKey];
    }
    
    /**
     * Delete subKey from this session level
     * @param type $subKey
     */
    public function delete($subKey)
    {
        unset($this->asset[$subKey]);
    }
    
    /**
     * Clear this session (erase)
     */
    public function clear()
    {
        unset($this->root[$this->key]);
        $this->keyTree = array();
    }
}