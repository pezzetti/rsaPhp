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
			if(q.value == p.value){
				alert("Selecione valores diferentes para P e Q");
				return false;
			}
            return true;
        }