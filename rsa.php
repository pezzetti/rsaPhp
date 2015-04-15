<?php
class RSA{
    
    /**
     * Calcula o Z e chama a função que procura possiveis E
     */
  public function getPossiveisE($p, $q){
      $this->p = $p;
      $this->q = $q;
    //Calcula Z              
    $this->z = bcmul(bcsub($p, 1), bcsub($q, 1));      
    // Retorna array de E's possiveis
    $e = $this->encontraE($this->z, $p,$q);    
    return $e;
  }
 
    /* 
    * Faz o Maior Divisor Comum
    */
    private function MDC($e,$m) {
      /*	$y = $e;
      	$x = $m;

      	while (bccomp($y, 0) != 0) {
        		// modulus function
            $w = bcsub($x, bcmul($y, bcdiv($x, $y, 0)));;
        		$x = $y;
        		$y = $w;
      	}

      	return $x;*/
        $a = max($e,$m);
        $b = min($e,$m);
        if($a%$b == 0){
            return $b;
        }else{
            return $this->MDC($b,($a%$b));
        }        
    }
    /**
     * Encontra E 
     */
    private function encontraE($z,$p,$q){                      
        for($i=2;$i<1000;$i++){
            if(bccomp($this->MDC($i, $z), '1')== 0 && $i != $p &&  $i != $q  ){
               $result[]= $i; 
            }
        }        
        return $result;
    }    
    
   /**
    * Encontra D
    */
   public function encontraD($e,$z){
       //echo "\n E $e";
       
       //while(!is_int($d =($z+1)/$e) || ($d =($z+1)/$e == $this->p) ||  ($d =($z+1)/$e ==$this->q) ||  ($d =($z+1)/$e == $e)){
       $d = ($z+1)/$e;
       while(!is_int($d) || $d == $e || $d == $this->p || $d == $this->q){
           //echo "\n Z ->". $z." D-> $d<br/>";           
           $d = ($z+1)/$e;
           $z = $z+$this->z;
           
       }       
       return $d;
       
   }
}

?>