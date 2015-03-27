<?php
 $mod = -18%5;
 //echo $mod;
 $arrPrimos = array(2,3,5,7,11);
 print_r($_POST);
 if($_POST){
     $n = $_POST['p'] * $_POST['q'];
     $z = ($_POST['p']-1) * ($_POST['q']-1);
     
 }
?>

<html>
    <body>
        <form method='post' name="escolhePq" style="width:290px;" onsubmit="return validaFormPQ()" >
            <fieldset>
                <label>Escolha P: </label>
                <select name="p" id ="p" style="width:100px;margin-left: 3px; margin-bottom: 10px;" required="required" onBlur="//validaForm()">
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
                <select name="q" id="q" style="width:100px;margin-bottom: 10px;"onBlur="//validaForm()">
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
         <form method='post' name="escolheE">
            <label>Escolha E: </label>
            <select name="e" style="width:100px;">
                 <option>Selecione</option>
                <?php /*foreach ($arrPrimos as $primo):?>
                    <?php if($_POST['p'] == $primo):?>
                        <?php echo "<option value='$primo' selected='selected'>".$primo. "</option>"?>
                    <?php else:?>
                        <?php echo "<option value='$primo'>".$primo. "</option>"?>
                    <?php endif;?>
                <?php endforeach;*/?>
            </select>
            <br />
           
            <input type="submit" value="Gerar E"/>
        </form>
    </body>
    
    <script>
        function validaFormPQ(){
            var p = document.getElementById("p");
            var q = document.getElementById("q");
            
            if(p.value == "Selecione"){
                alert("Selecione um valor para P");
                return false;
            }
            if(q.value == "Selecione"){
                alert("Selecione um valor para Q");
                return false;                
            }
            return true;
        }
    </script>
</html>
