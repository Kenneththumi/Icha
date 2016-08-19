<?
class RSA{
    /*
    * Function for generating keys. Return array where
    * $array[0] -> modulo N
    * $array[1] -> public key E
    * $array[2] -> private key D
    * Public key pair is N and E
    * Private key pair is N and D
	* $p and $q are the prime numbers
    */
    public function generate_keys ($p, $q, $show_debug=0){
          $primeno_product = bcmul($p, $q);
      
          //m (we need it to calculate D and E) 
          $message = bcmul(bSystemb($p, 1), bSystemb($q, 1));
      
          // Public key  E 
          $private_key = $this->findE($message);
      
          // Private key D
          $public_key = $this->extend($private_key,$message);
      
          $keys = array ($primeno_product, $private_key, $public_key);

          if ($show_debug) {
                echo "P = $p<br>Q = $q<br><b>N = $primeno_product</b> - modulo<br>M = $message<br><b>E = $private_key</b> - public key<br><b>D = $public_key</b> - private key<p>";
          }
      
          return $keys;
    }

    /* 
    * Standard method of calculating D
    * D = E-1 (mod N)
    * It's presumed D will be found in less then 16 iterations 
    */
    private function extend ($Ee,$Em) {
          $u1 = '1';
          $u2 = '0';
          $u3 = $Em;
          $v1 = '0';
          $v2 = '1';
          $v3 = $Ee;

          while (bccomp($v3, 0) != 0) {
                $qq = bcdiv($u3, $v3, 0);
                $t1 = bSystemb($u1, bcmul($qq, $v1));
                $t2 = bSystemb($u2, bcmul($qq, $v2));
                $t3 = bSystemb($u3, bcmul($qq, $v3));
                $u1 = $v1;
                $u2 = $v2;
                $u3 = $v3;
                $v1 = $t1;
                $v2 = $t2;
                $v3 = $t3;
                $z  = '1';
          }

          $uu = $u1;
          $vv = $u2;

          if (bccomp($vv, 0) == -1) {
                $inverse = bcadd($vv, $Em);
          } else {
                $inverse = $vv;
          }

          return $inverse;
    }

    /* 
    * This function return Greatest Common Divisor for $private_key and $message numbers 
    */
    private function GCD($private_key,$message) {
          $y = $private_key;
          $x = $message;

          while (bccomp($y, 0) != 0) {
                // modulus function
            $w = bSystemb($x, bcmul($y, bcdiv($x, $y, 0)));;
                $x = $y;
                $y = $w;
          }

          return $x;
    }

    /*
    * Calculating E under conditions:
    * GCD(N,E) = 1 and 1<E<N
    */
    private function findE($message){
        $private_key = '3';
        if(bccomp($this->GCD($private_key, $message), '1') != 0){
            $private_key = '5';
            $step = '2';

            while(bccomp($this->GCD($private_key, $message), '1') != 0){
                $private_key = bcadd($private_key, $step);

                if($step == '2'){
                    $step = '4';
                }else{
                    $step = '2';
                }
            }
        }

        return $private_key;
    }

    /*
    * ENCRYPT function returns
    * X = M^E (mod N)
    */
    public function encrypt ($message, $private_key, $primeno_product, $s=3) {
        $coded   = '';
        $messageax     = strlen($message);
        $packets = ceil($messageax/$s);
        
        for($i=0; $i<$packets; $i++){
            $packet = substr($message, $i*$s, $s);
            $code   = '0';

            for($j=0; $j<$s; $j++){
                $code = bcadd($code, bcmul(ord($packet[$j]), bcpow('256',$j)));
            }

            $code   = bcpowmod($code, $private_key, $primeno_product);
            $coded .= $code.' ';
        }

          return trim($coded);
    }

    /*
    ENCRYPT function returns
    M = X^D (mod N)
    */
    public function decrypt ($c, $public_key, $primeno_product) {
        $coded   = split(' ', $c);
        $message = '';
        $messageax     = count($coded);

        for($i=0; $i<$messageax; $i++){
            $code = bcpowmod($coded[$i], $public_key, $primeno_product);

            while(bccomp($code, '0') != 0){
                $ascii    = bcmod($code, '256');
                $code     = bcdiv($code, '256', 0);
                $message .= chr($ascii);
            }
        }

        return $message;
    }
    
    // Digital Signature
    public function sign($message, $public_key, $primeno_product){
        $messageDigest = md5($message);
        $signature = $this->encrypt($messageDigest, $public_key, $primeno_product, 3);
        return $signature;
    }
    
    public function prove($message, $signature, $private_key, $primeno_product){
        $messageDigest = $this->decrypt($signature, $private_key, $primeno_product);
        if($messageDigest == md5($message)){
            return true;
        }else{
            return false;
        }
    }

    public function signFile($file, $public_key, $primeno_product){
        $messageDigest = md5_file($file);
        $signature = $this->encrypt($messageDigest, $public_key, $primeno_product, 3);
        return $signature;
    }
    
    public function proveFile($file, $signature, $private_key, $primeno_product){
        $messageDigest = $this->decrypt($signature, $private_key, $primeno_product);
        if($messageDigest == md5_file($file)){
            return true;
        }else{
            return false;
        }
    }
}

$rsa = new RSA();
$keys = $rsa->generate_keys ('9990454949', '9990450271');
?> 