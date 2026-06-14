<?php
class Keccak {
  private static $RC=['0000000000000001','0000000000008082','800000000000808a','8000000080008000','000000000000808b','0000000080000001','8000000080008081','8000000000008009','000000000000008a','0000000000000088','0000000080008009','000000008000000a','000000008000808b','800000000000008b','8000000000008089','8000000000008003','8000000000008002','8000000000000080','000000000000800a','800000008000000a','8000000080008081','8000000000008080','0000000080000001','8000000080008008'];
  private static $r=[0,1,62,28,27,36,44,6,55,20,3,10,43,25,39,41,45,15,21,8,18,2,61,56,14];
  static function hash($msg,$bits){ return self::keccak($msg,(1600-2*$bits)/8,$bits/8,0x01); }
  private static function keccak($msg,$rate,$out,$pad){
    $st=array_fill(0,25,['0','0']); // [hi,lo] hex non usato, useremo gmp 64
    $S=array_fill(0,25,gmp_init(0));
    $msg=array_values(unpack('C*', $msg)); $len=count($msg);
    $blk=$rate; $ptr=0;
    $blocks=[];
    // padding pad10*1
    $m=$msg; $m[]=$pad; while(count($m)%$blk!=0)$m[]=0x00; $m[count($m)-1]|=0x80;
    for($off=0;$off<count($m);$off+=$blk){
      for($i=0;$i<$blk;$i++){ $lane=intval($i/8); $byte=$i%8;
        $S[$lane]=gmp_xor($S[$lane],gmp_mul(gmp_init($m[$off+$i]),gmp_pow(2,8*$byte))); }
      $S=self::perm($S);
    }
    $out_bytes='';
    $i=0;
    while(strlen($out_bytes)<$out){ $lane=$S[$i];
      for($b=0;$b<8;$b++){ $out_bytes.=chr(gmp_intval(gmp_and(gmp_div_q($lane,gmp_pow(2,8*$b)),gmp_init(255)))); }
      $i++; }
    return bin2hex(substr($out_bytes,0,$out));
  }
  private static function rotl($x,$n){ $n%=64; if($n==0)return self::m64($x);
    return self::m64(gmp_or(gmp_mul($x,gmp_pow(2,$n)),gmp_div_q($x,gmp_pow(2,64-$n)))); }
  private static function m64($x){ return gmp_and($x,gmp_init('FFFFFFFFFFFFFFFF',16)); }
  private static function perm($A){
    for($round=0;$round<24;$round++){
      $C=[]; for($x=0;$x<5;$x++){ $C[$x]=gmp_xor(gmp_xor(gmp_xor(gmp_xor($A[$x],$A[$x+5]),$A[$x+10]),$A[$x+15]),$A[$x+20]); }
      $D=[]; for($x=0;$x<5;$x++){ $D[$x]=gmp_xor($C[($x+4)%5],self::rotl($C[($x+1)%5],1)); }
      for($x=0;$x<5;$x++)for($y=0;$y<5;$y++){ $A[$x+5*$y]=gmp_xor($A[$x+5*$y],$D[$x]); }
      $B=array_fill(0,25,gmp_init(0));
      for($x=0;$x<5;$x++)for($y=0;$y<5;$y++){ $B[$y+5*((2*$x+3*$y)%5)]=self::rotl($A[$x+5*$y],self::$r[$x+5*$y]); }
      for($x=0;$x<5;$x++)for($y=0;$y<5;$y++){ $A[$x+5*$y]=gmp_xor($B[$x+5*$y],gmp_and(gmp_com($B[(($x+1)%5)+5*$y]),$B[(($x+2)%5)+5*$y])); }
      $A[0]=gmp_xor($A[0],gmp_init(self::$RC[$round],16));
    }
    return $A;
  }
}
