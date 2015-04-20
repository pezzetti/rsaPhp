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
   function getPossiveisE($p, $q){  
    // Retorna array de E's possiveis
    $e = $this->encontraE($this->z, $this->p,$this->q);    
    return $e;
  }
 
    /* 
    * Faz o Maior Divisor Comum
    */
     function MDC($e,$m) {
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
     function encontraE($z,$p,$q){                      
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
    function encontraD($e,$z){
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
   
   function mod($a, $b){
       	$mod = gmp_div_r($a,$b);		
        while($mod < 0){            
            $mod = $mod+$b;
        }						
        return gmp_strval($mod);
    }
   
    function montaTexto($mensagem, $n, $e){
        $texto = "";         
        for ($i = 0; $i < strlen($mensagem); $i++){ 
            if($i>0){
                $texto .= "|";                
            }            
            $texto .= $this->calculaOperacaoModular(ord($mensagem[$i]), $n, $e);
        } 
        return $texto;
    }
        
    
     function calculaOperacaoModular($letra, $n, $expoente) {
         // expoente em binário e depois coloca no array inverso para fazer os calculos dos mods na sequencia correta.
        $expoenteBinario = decbin($expoente);        
        $arrayBinario = str_split(strrev($expoenteBinario));
        $ultimaMultiplicacao = gmp_init(1);
        $letra = strval($letra);
        $nString= strval($n);
        foreach ($arrayBinario as $chave => $valorBinario) {
            $l = gmp_init($letra);
            $n = gmp_init($nString);           
            // só faz o que tem valor 1 no numero binário
            if($valorBinario == 1){        
                // numero de iterações da operacao modular
                $i = $chave;
                if($chave != 0){
                    while($i > 0){
                        // vai armazenando o calculos dos mods na $m para depois fazer na ultima multiplicacao
                        $l = $this->mod(gmp_pow ($l,"2"),$n);
                        $i--;
                    }  
                }else{
                    // posicao zero do array nao faz mod ao quadrado
                    $l = $this->mod($l,$n);                       
                }                
                // vai armazenando a multiplicacao entre os resultados dos binarios [  ]
                $ultimaMultiplicacao = gmp_mul($ultimaMultiplicacao, $this->mod($l,$n));
            }
        }
        //retorna o ultimo mod ->  [ ]mod n
        return gmp_strval($this->mod($ultimaMultiplicacao,$n));
    }
    
     function decifraAsc($mensagem, $n, $d){
        $mensagem = explode("|",$mensagem);
        $msgDecifrada = array();
        foreach($mensagem as $letraEmASC){ 
            $msgDecifrada[] = $this->calculaOperacaoModular($letraEmASC, $n, $d);
        } 
        return implode("|",$msgDecifrada);
    }
    
    function ascParaTextoNormal($mensagem){
        $mensagem = explode("|",$mensagem); 
        $mensagemNormal = "";
        foreach($mensagem as $letraAsc){ 
            $mensagemNormal .= chr($letraAsc);
        } 
        return $mensagemNormal;
    }
}

?>