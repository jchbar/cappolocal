<script language="javascript"> 

function cargarContenido(){ 
   var ajax1 = false;
            
   if (window.XMLHttpRequest){
      ajax1 = new XMLHttpRequest ();
   }
   else if (window.ActiveXObject){    
      try{
         ajax1 = new ActiveXObject ("Msxml2.XMLHTTP");
      }
      catch (e){       
         try{
            ajax1 = new ActiveXObject ("Microsoft.XMLHTTP");
         }
         catch (e){
         }
      }
   } 
   
      
    var number = document.getElementById("monpre_sdp").value ; 
	alert(number);
 
    ajax1.open("POST", "info.php", true ); 
    ajax1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
    ajax1.send("&number="+number); 
 
    ajax1.onreadystatechange = function(){  
        if (ajax1.readyState == 4){ 
            Valor =  ajax1.responseText
         document.getElementById("result").innerHTML = Valor + "<br><a href='Siguiente_Pagina.php?Valor=" + Valor + "'>Continuar</a>"
        }  
        else{ 
            document.getElementById("result").innerHTML = "Aguarde, calculando..."; 
        } 
    } 
} 
</script>