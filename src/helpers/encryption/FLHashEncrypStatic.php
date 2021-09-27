<?php
namespace fl\cms\helpers\encryption;

/**
 * Crypt
 * @author Anstoliy Smirnov
 */
class FLHashEncrypStatic
{
    const ENCRYPTION_KEY = 'e3f080b6edfcf6fff70654021c7c2e53';

        /**
     * Encrypt
     * @param string $plaintext
     * @param mixed $param
     * @return string
     */  
    public function encrypt($plaintext, $param = null) {
        return self::opensslEncrypt($plaintext);
    }
    
    /**
     * Decrypt
     * @param string $ciphertext
     * @param mixed $param
     * @return string
     */
    public function decrypt($ciphertext, $param = null) {
        return self::opensslDecrypt($ciphertext);
    }
    
    /**
     * Get hash
     * @author Anatoliy Smirnov
     * @param string $data
     * @param string $type
     */
    public function getHash($data, $type = null){
        switch ($type) {
        case "md5":
            return self::getHashMd5($data);
            break;
        default:
            return self::getHashMd5($data);
        }
    }

    /**
     * Encrypt
     * @author Anstoliy Smirnov
     * @param string $plaintext
     * @return string
     */
    private function opensslEncrypt($plaintext) 
    {
//        return exec("curl -d '$plaintext' -X POST http://localhost:8765/encrypt");
//        $contents = json_decode(exec('curl -d \'{"data":"'.$plaintext.'"}\' -X POST http://localhost:8765/encrypt'), true);
//        return $contents['data'];
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, self::ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, self::ENCRYPTION_KEY, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
        return $ciphertext;
    }
  
    /**
     * Decrypt
     * @author Anstoliy Smirnov
     * @param string $ciphertext
     * @return string
     */
    private function opensslDecrypt($ciphertext) 
    {
//        return exec("curl -d '$ciphertext' -X POST http://localhost:8765/decript");
        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $plaintext = openssl_decrypt($ciphertext_raw, $cipher, self::ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, self::ENCRYPTION_KEY, $as_binary=true);
        if (hash_equals($hmac, $calcmac))
        {
            return $plaintext;
        } else {
            return false;
        }
    }
    
    /**
     * Get hashMD5
     * @author Anatoliy Smirnov
     * @param string $data
     * @return string
     */
    private function getHashMd5($data) {
//        return  exec("curl -d '$data' -X POST http://localhost:8765/gethash");
//$sURL = "http://localhost:8765/gethash"; // URL-адрес POST 
//        $aHTTP = array(
//          'http' =>
//            array(
//            'method'  => 'POST',
//            'header'  => 'Content-type: application/x-www-form-urlencoded',
//            'content' => json_encode(['data' => $data])
//          )
//        );
//        $context = stream_context_create($aHTTP);
//        $contents = json_decode(file_get_contents($sURL, false, $context), true);
//        return $contents['data'];
        return md5($data . self::ENCRYPTION_KEY);
    }
}