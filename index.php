<?php
  require 'rsa.php';

 $arrPrimos = array(7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97, 101, 103, 107, 109, 113, 127, 131, 137, 139, 149, 151, 157, 163, 167, 173, 179, 181, 191, 193, 197, 199, 211, 223, 227, 229, 233, 239, 241, 251, 257, 263, 269, 271, 277, 281, 283, 293, 307, 311, 313, 317, 331, 337, 347, 349, 353, 359, 367, 373, 379, 383, 389, 397, 401, 409, 419, 421, 431, 433, 439, 443, 449, 457, 461, 463, 467, 479, 487, 491, 499, 503, 509, 521, 523, 541, 547, 557, 563, 569, 571, 577, 587, 593, 599, 601, 607, 613, 617, 619, 631, 641, 643, 647, 653, 659, 661, 673, 677, 683, 691, 701, 709, 719, 727, 733, 739, 743, 751, 757, 761, 769, 773, 787, 797, 809, 811, 821, 823, 827, 829, 839, 853, 857, 859, 863, 877, 881, 883, 887, 907, 911, 919, 929, 937, 941, 947, 953, 967, 971, 977, 983, 991, 997, 1009, 1013);
 
 if($_POST){        
    $rsa = new RSA($_POST['p'], $_POST['q']);
 
   $possiveisE = $rsa->getPossiveisE($_POST['p'], $_POST['q']);   
    //echo "Z-> $rsa->z";
    $n = bcmul($_POST['p'], $_POST['q']);
    if(isset($_POST['e'])){ 
        $eEscolhido = $_POST['e'];
        $d = $rsa->encontraD($eEscolhido, $rsa->z);      
    }
    if(isset($_POST['mensagemParaCifrar'])){
        $msg = $_POST['mensagemParaCifrar'];
        $retorno = $rsa->montaTexto($msg, $n, $eEscolhido);
        echo "<div style ='width:1000px; height:auto; border:2px solid #888; margin: 0 auto;'>";
            echo "<p> Mensagem em ASC: ";
            for($i = 0; $i<  strlen($msg);$i++){
                echo ord($msg[$i]). " ";
            }        
            echo "<textarea style ='width: 900px;height: 100px; margin:5px;'> Mensagem Cifrada: ". $retorno . "</textarea>";
        echo "</div>";
    }
    if(isset($_POST['mensagemParaDecifrar'])){
          $msg = $_POST['mensagemParaDecifrar'];
          $retorno = $rsa->decifraAsc($msg,$n, $_POST['d']);
          echo "<div style ='width:1000px; height:auto; border:2px solid #888; margin: 0 auto;'>";
            echo "<p>Texto Descifrado em ASC: ". $retorno;
            $convertido = $rsa->ascParaTextoNormal($retorno);
            echo "<p> Texto corrreto: ". $convertido;
          echo "</div>";
    }
 } 
?>

<html>
    <head>
        <script type="text/javascript" src="validaForm.js" ></script>
    </head>
    <body>
        <form method='post' name="escolhePq" style="width:290px;" onsubmit="return validaFormPQ()" >
            <fieldset>
                <label>Escolha P: </label>
                <select name="p" id ="p" style="width:100px;margin-left: 3px; margin-bottom: 10px;" required="required" onBlur="validaForm()">
                    <option>Selecione</option>
                    <?php foreach ($arrPrimos as $primo):?>
                        <?php if($_POST['p'] == $primo):?>
                            <?php echo "<option value='$primo' selected='selected'>".$primo. "</option>"?>
                        <?php else:?>
                            <?php echo "<option value='$primo'>".$primo. "</option>"?>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
                <br />
                <label>Escolha Q: </label>
                <select name="q" id="q" style="width:100px;margin-bottom: 10px;"onBlur="validaForm()">
                    <option>Selecione</option>
                    <?php foreach ($arrPrimos as $primo):?>
                        <?php if($_POST['q'] == $primo):?>
                            <?php echo "<option value='$primo' selected='selected'>".$primo. "</option>"?>
                        <?php else:?>
                            <?php echo "<option value='$primo'>".$primo. "</option>"?>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
                <br />
                <input type="submit" value="Gerar E" id ="enviapq" />
            </fieldset>
        </form>
        <div style="width:150px;height:79px; border:1px solid #888; padding:5px;margin: 5px; float:left;"> 
           <p>Z: <?php echo $rsa->z;?>
           <p>N: <?php echo $rsa->n;?>
        </div>
         <form method='post' name="escolheE" style="width:290px;">
            <fieldset style=" height: 71px;    margin: 21px;    width: 155px;">
                <label>Escolha E: </label>
                <select name="e" style="width:100px;">
                     <option>Selecione</option>
                    <?php foreach ($possiveisE as $e):?>
                        <?php if($_POST['e'] == $e):?>
                            <?php echo "<option value='$e' selected='selected'>".$e. "</option>"?>
                        <?php else:?>
                            <?php echo "<option value='$e'>".$e. "</option>"?>
                        <?php endif;?>
                    <?php endforeach;?>
                </select>
                <br />
                <input type="hidden" name="q" value="<?php echo $_POST['q'];?>" />
                <input type="hidden" name="p" value="<?php echo $_POST['p'];?>" />
                <input type="submit" value="Gerar D"/>
            </fieldset>
        </form>
        <?php if(isset($_POST['e'])):?>
            <div style="width:200px;height:100px; border:2px solid #888; margin-bottom: 10px;">                        
                <p>Chave publica <?php echo "($eEscolhido,$n)";?>
                <p>Chave privada <?php echo "($d,$n)";?>
            </div>        
            <div style="width:350px;height:200px; border:1px solid #888; padding:5px;">
                <form method='post' name="cifraMensagem">
                    <p>Digite a mensagem a ser cifrada
                    <textarea cols='35' rows='5' name="mensagemParaCifrar"><?php echo $_POST['mensagemParaCifrar']?></textarea>
                    <input type="hidden" name="q" value="<?php echo $_POST['q'];?>" />
                    <input type="hidden" name="p" value="<?php echo $_POST['p'];?>" />
                    <input type="hidden" name="e" value="<?php echo $_POST['e'];?>" />
                    <input type="hidden" name="d" value="<?php echo $d;?>" />
               
                    <input type='submit' value="Cifrar Mensagem" style="margin-top:5px;"/>    
                </form>
            </div>
        <?php endif;?>
          <div style="width:350px;height:200px; border:1px solid #888; padding:5px;">
                <form method='post' name="decrifra">
                    <p>Digite a mensagem a ser cifrada
                    <textarea cols='35' rows='5' name="mensagemParaDecifrar"><?php echo $_POST['mensagemParaDecifrar']?></textarea>
                    <input type="hidden" name="q" value="<?php echo $_POST['q'];?>" />
                    <input type="hidden" name="p" value="<?php echo $_POST['p'];?>" />
                    <input type="hidden" name="e" value="<?php echo $_POST['e'];?>" />
                    <input type="hidden" name="mensagemParaCifrar" value="<?php echo $_POST['mensagemParaCifrar'];?>" />
                    <input type="hidden" name="d" value="<?php echo $d;?>" />               
                    <input type='submit' value="Decifrar Mensagem" style="margin-top:5px;"/>    
                </form>
            </div>
    </body>
        
</html>
