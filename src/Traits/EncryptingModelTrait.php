<?php namespace Esensi\Model\Traits;

use \Illuminate\Support\Facades\Crypt;
use \Illuminate\Encryption\Encrypter;
use \Illuminate\Encryption\DecryptException;

/**
 * Trait that implements the EncryptingModelInterface
 *
 * @package Esensi\Model
 * @author Daniel LaBarge <wishlist@emersonmedia.com>
 * @copyright 2014 Emerson Media LP
 * @license https://github.com/esensi/model/blob/master/LICENSE.txt MIT License
 * @link http://www.emersonmedia.com
 *
 * @see \Esensi\Model\Contracts\EncryptionModelInterface
 */
trait EncryptingModelTrait {

    /**
     * Whether the model is encrypting or not
     *
     * @var boolean
     */
    protected $encrypting = true;

    /**
     * The Encrypter to use for encryption
     *
     * @var \Illuminate\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * Get the encryptable attributes
     *
     * @return array
     */
    public function getEncryptable()
    {
        return $this->encryptable ?: [];
    }

    /**
     * Set the encryptable attributes
     *
     * @param  array $attributes to encrypt
     * @return void
     */
    public function setEncryptable( array $attributes )
    {
        $this->encryptable = $attributes;
    }

    /**
     * Returns whether or not the model will encrypt
     * attributes when setting and decrypt when getting
     *
     * @return boolean
     */
    public function getEncrypting()
    {
        return $this->encrypting;
    }

    /**
     * Set whether or not the model will encrypt attributes
     * when setting and decrypt when getting
     *
     * @param  boolean
     * @return void
     */
    public function setEncrypting( $value )
    {
        $this->encrypting = (bool) $value;
    }

    /**
     * Set the Encrypter to use for encryption
     *
     * @return \Illuminate\Encryption\Encrypter
     */
    public function getEncrypter()
    {
        return $this->encrypter ?: new Crypt;
    }

    /**
     * Set the Encrypter to use for encryption
     *
     * @param \Illuminate\Encryption\Encrypter $encrypter
     * @return void
     */
    public function setEncrypter( Encrypter $encrypter )
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Returns whether the attribute is encryptable
     *
     * @param string $attribute name
     * @return boolean
     */
    public function isEncryptable( $attribute )
    {
        return $this->getEncrypting()
            && in_array( $attribute, $this->getEncryptable() );
    }

    /**
     * Returns whether the attribute is encrypted
     *
     * @param string $attribute name
     * @return boolean
     */
    public function isEncrypted( $attribute )
    {
        if( ! array_key_exists($attribute, $this->attributes) )
        {
            return false;
        }

        try
        {
            $this->decrypt( $this->getAttribute( $attribute ) );
        }
        catch (DecryptException $exception)
        {
            return false;
        }

        return true;
    }

    /**
     * Returns whether the attribute is decrypted
     *
     * @param string $attribute name
     * @return boolean
     */
    public function isDecrypted( $attribute )
    {
        return ! $this->isEncrypted ( $attribute );
    }

    /**
     * Encrypt attributes that should be encrypted
     *
     * @return void
     */
    public function encryptAttributes()
    {
        foreach( $this->getEncryptable() as $attribute )
        {
            $this->setEncryptingAttribute( $attribute, $this->getAttribute($attribute) );
        }
    }

    /**
     * Return an encrypted string for the value
     *
     * @param string $value
     * @return string
     */
    public function encrypt( $value )
    {
        return $this->getEncrypter()
            ->encrypt( $value );
    }

    /**
     * Return a decrypted string for the value
     *
     * @param string $value
     * @return string
     */
    public function decrypt( $value )
    {
        return $this->getEncrypter()
            ->decrypt( $value );
    }

    /**
     * Get the decrypted value for an encrypted attribute
     *
     * @param string $attribute name
     * @return string
     */
    function getEncryptedAttribute( $attribute )
    {
        return $this->decrypt( $this->getAttribute($attribute) );
    }

    /**
     * Set an encrypted value for an encryptable attribute
     *
     * @param string $attribute name
     * @param string $value to encrypt
     * @return void
     */
    function setEncryptingAttribute( $attribute, $value )
    {
        $this->attributes[ $attribute ] = $this->encrypt( $value );
    }

}
