<?php
class RSA{
    
    public function RSA($p, $q){
         $this->z = bcmul(bcsub($p, 1), bcsub($q, 1));     
         $this->p = $p;
         $this->q = $q;
         $this->n = bcmul($p, $q);     
    }
    /**
     * Calcula o Z e chama a função que procura possiveis E
     */
  public function getPossiveisE($p, $q){
    /*  $this->p = $p;
      $this->q = $q;
    //Calcula Z              
    $this->z = bcmul(bcsub($p, 1), bcsub($q, 1));    */  
    // Retorna array de E's possiveis
    $e = $this->encontraE($this->z, $this->p,$this->q);    
    return $e;
  }
 
    /* 
    * Faz o Maior Divisor Comum
    */
    private function MDC($e,$m) {
      /*	$y = $e;
      	$x = $m;

      	while (bccomp($y, 0) != 0) {        		
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
          // echo "\n Z ->". $z." D-> $d<br/>";           
           $d = (bcadd($z,1))/$e;
           $z = $z+$this->z;         
           
       }        
       //  echo "\n Z ->". $z." D-> $d<br/>";     
       return $d;       
   }
   
    function montaTexto($mensagem, $n, $e){
        $texto = ""; 
        for ($i = 0; $i < strlen($mensagem); $i++){ 
            if($i>0){
                $texto .= "|";                
            }            
            $texto .= $this->cifra(ord($mensagem[$i]), $n, $e);
        } 
        return $texto;
    }
   
     function mod($a, $b){
       	$mod = gmp_div_r($a,$b);		
        while($mod < 0){            
            $mod = $mod+$b;
        }						
        return gmp_strval($mod);
    }
    
    public function cifra($mensagem, $n, $e) {
        $e_binario = decbin($e);
        $arrE = str_split(strrev($e_binario));
        $multiplicacaofinal = gmp_init(1);
        $m_original = strval($mensagem);
        $n_original = strval($n);
        foreach ($arrE as $key => $value) {
            $m = gmp_init($m_original);
            $n = gmp_init($n_original);
            if($value == 1){        
                $i = $key;
                if($key == 0){
                    $m = $this->mod($m,$n); 
                }else{
                    while($i > 0){
                        $m = $this->mod(gmp_pow ($m,"2"),$n);
                        $i--;
                    }    
                }                
                $multiplicacaofinal = gmp_mul($multiplicacaofinal, $this->mod($m,$n));
            }
        }
        return gmp_strval($this->mod($multiplicacaofinal,$n));
    }
    
     function montaTextoDecifradoASCII($mensagem, $n, $d){
        $mensagem = explode("|",$mensagem);
        $msgDecifrada = array();
        foreach($mensagem as $letraEmASC){ 
            $msgDecifrada[] = $this->cifra($letraEmASC, $n, $d);
        } 
        return implode("|",$msgDecifrada);
    }
    
    function montaTextoDecifrado($m){
        $m = explode("|",$m); 
        foreach($m as $charASCII){ 
            $M .= chr($charASCII);
        } 
        return $M;
    }
}

?>