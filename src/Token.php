<?php

namespace Aetiom\PhpUtils;

/**
 * Token
 *
 * @author Aetiom <aetiom@protonmail.com>
 * @package PHP-Utils
 * @version 1.0
 */
class Token
{
    /**
     * @var string $key : token key 
     */
    protected $key;
    
    /**
     * @var int $created : creation unix timestamp
     */
    protected $created;
    
    /**
     * @var int $expiring : expiring unix timestamp
     */
    protected $expiring;
    
    /**
     * @var int $revokeOn : revocation unix timestamp
     */
    protected $revoked;
    
    
    
    
    /**
     * Get token key
     * @return string token id
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * Get created time
     * @return int created unix timestamp
     */
    public function getCreatedTime()
    {
        return $this->created;
    }
    
    /**
     * Get expiring time
     * @return int expiring unix timestamp
     */
    public function getExpiringTime()
    {
        return $this->expiring;
    }
    
    /**
     * Get revoked time
     * @return int revoked unix timestamp
     */
    public function getRevokedTime()
    {
        return $this->revoked;
    }
    
    /**
     * Get all current token data
     * 
     * @return array with all current token data 
     * (formatted as the constructor needs it)
     */
    public function getAllTokenData()
    {
        return [
            'key'      => $this->key,
            'created'  => $this->created,
            'expiring' => $this->expiring,
            'revoked'  => $this->revoked
        ];
    }
    
    
       
    /**
     * Determine if current token has expired or not
     * @return bool true if token has expired, false otherwise
     */
    public function hasExpired()
    {
        if ($this->expiring < time()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Determine if current token is revoked or not
     * @return bool true if token is revoked, false otherwise
     */
    public function isRevoked()
    {
        if (!empty($this->revoked)) {
            return true;
        }
        
        return false;
    }
    
    
    
    /**
     * Constructor
     * 
     * @param string $formId : form id
     * @param \PhpForm\Form\Options $options
     */
    public function __construct($data = []) 
    {   
        if (empty($data)) {
            $this->create();
        }
        
        $this->key      = $data['key'] ?? '';
        $this->created  = $data['created'] ?? 0;
        $this->expiring = $data['expiring'] ?? 0;
        $this->revoked  = $data['revoked'] ?? 0;
    }
    
    
    
    /**
     * Create and save new token key
     * 
     * @param int $expire : token expire time in seconds
     * @param int $length : token key length
     * 
     * @return string : token key
     */
    public function create($expire = 300, $length = 32)
    {
        $this->key      = bin2hex(random_bytes($length/2));
        $this->created  = time();
        $this->expiring = $this->created+$expire;
        $this->revoked  = 0;
        
        return $this->getAllTokenData();
    }
    
    /**
     * Refresh token, adding expire time to current time
     * 
     * @param int $expire : token expire time
     * @return bool true if refresh is successful, false otherwise
     * 
     * @throws \Exceptions if token has not been set before
     */
    public function refresh($expire = 300)
    {
        if (!isset($this->key) || empty($this->key)) {
            throw new \Exceptions('token has not been set');
        }
        
        if (isset($this->revoked) && !empty($this->revoked)
                || $this->expiring < time()) {
            return false;
        }
        
        $this->expiring = time() + $expire;
        return true;
    }
    
    /**
     * Revoke token
     * @param int $time : revocation time
     */
    public function revoke($time = 0) 
    {
        if (empty($time)) {
            $time = time();
        }
        
        $this->revoked = $time;
    }
}