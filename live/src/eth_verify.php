<?php
// Se l estensione GMP non c'è, il login wallet si spegne da solo e il login email resta vivo.
if(!extension_loaded('gmp')){ define('ETH_VERIFY_ON',false); if(!function_exists('ethVerifyPersonalSign')){ function ethVerifyPersonalSign($m,$s,$a){ return false; } } return; }
define('ETH_VERIFY_ON',true);
// Verifica firma personal_sign Ethereum, secp256k1 ecrecover + keccak256, in PHP puro con GMP.
// Nessuna dipendenza esterna. Usato per il login con wallet, lato server.

function kc_keccak256($bin){ return Keccak::hash($bin,256); }

// ---- secp256k1 ----
class Sec {
  public static $p,$n,$a,$b,$gx,$gy;
  static function init(){
    self::$p=gmp_init('FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFEFFFFFC2F',16);
    self::$n=gmp_init('FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFEBAAEDCE6AF48A03BBFD25E8CD0364141',16);
    self::$a=gmp_init(0); self::$b=gmp_init(7);
    self::$gx=gmp_init('79BE667EF9DCBBAC55A06295CE870B07029BFCDB2DCE28D959F2815B16F81798',16);
    self::$gy=gmp_init('483ADA7726A3C4655DA4FBFC0E1108A8FD17B448A68554199C47D08FFB10D4B8',16);
  }
  static function inv($x,$m){ return gmp_invert($x,$m); }
  static function add($P,$Q){
    if($P===null) return $Q; if($Q===null) return $P;
    list($x1,$y1)=$P; list($x2,$y2)=$Q;
    if(gmp_cmp($x1,$x2)==0 && gmp_cmp($y1,$y2)!=0) return null;
    if(gmp_cmp($x1,$x2)==0){
      $s=gmp_mod(gmp_mul(gmp_mul(gmp_init(3),gmp_mul($x1,$x1)),self::inv(gmp_mul(gmp_init(2),$y1),self::$p)),self::$p);
    } else {
      $s=gmp_mod(gmp_mul(gmp_sub($y2,$y1),self::inv(gmp_sub($x2,$x1),self::$p)),self::$p);
    }
    $x3=gmp_mod(gmp_sub(gmp_sub(gmp_mul($s,$s),$x1),$x2),self::$p);
    $y3=gmp_mod(gmp_sub(gmp_mul($s,gmp_sub($x1,$x3)),$y1),self::$p);
    return [$x3,$y3];
  }
  static function mul($k,$P){
    $R=null; while(gmp_cmp($k,0)>0){ if(gmp_testbit($k,0)) $R=self::add($R,$P); $P=self::add($P,$P); $k=gmp_div_q($k,gmp_init(2)); }
    return $R;
  }
}
Sec::init();

function eth_recover($msg,$sigHex){
  $sig=hex2bin(ltrim($sigHex,'0x')); if(strlen($sig)!==65) return null;
  $r=gmp_init(bin2hex(substr($sig,0,32)),16);
  $s=gmp_init(bin2hex(substr($sig,32,32)),16);
  $v=ord($sig[64]); if($v>=27) $v-=27; if($v!==0 && $v!==1) return null;
  // hash EIP-191 personal_sign
  $prefix="\x19Ethereum Signed Message:\n".strlen($msg).$msg;
  $h=gmp_init(kc_keccak256($prefix),16);
  $p=Sec::$p; $n=Sec::$n;
  // x = r (assumiamo r < p, recovery id basso)
  $x=$r;
  // y^2 = x^3 + 7
  $y2=gmp_mod(gmp_add(gmp_powm($x,gmp_init(3),$p),Sec::$b),$p);
  $y=gmp_powm($y2,gmp_div_q(gmp_add($p,gmp_init(1)),gmp_init(4)),$p);
  if((int)gmp_intval(gmp_mod($y,gmp_init(2)))!==$v){ $y=gmp_sub($p,$y); }
  $R=[$x,$y];
  $rInv=gmp_invert($r,$n);
  $u1=gmp_mod(gmp_mul(gmp_sub($n,gmp_mod($h,$n)),$rInv),$n);
  $u2=gmp_mod(gmp_mul($s,$rInv),$n);
  $Q=Sec::add(Sec::mul($u1,[Sec::$gx,Sec::$gy]),Sec::mul($u2,$R));
  if($Q===null) return null;
  $pub=str_pad(gmp_strval($Q[0],16),64,'0',STR_PAD_LEFT).str_pad(gmp_strval($Q[1],16),64,'0',STR_PAD_LEFT);
  $addr=substr(kc_keccak256(hex2bin($pub)),24);
  return '0x'.$addr;
}
