function showPassword(id){
    var element = document.getElementById(id);
    if (element.type == "password"){
        element.type="text";
    }
    else{
        element.type="password";
    }
}

// https://www.developpez.net/forums/d432337/javascript/general-javascript/changer-d-image-onclick/
function changeimg(monimage) {
 	var ancimage = monimage.src;

 	if(ancimage.substring(ancimage.lastIndexOf("/"), ancimage.length) == "/eyes.png"){
  		monimage.src= ancimage.substring(0,ancimage.lastIndexOf("/"), ancimage.length)+"/eyes2.png";
 
  	}else{
  		monimage.src= ancimage.substring(0,ancimage.lastIndexOf("/"), ancimage.length)+"/eyes.png";
  	}
}

// https://www.developpez.net/forums/d343653/javascript/general-javascript/poo-compte-rebours/
      var tps = 5 ;
      var s=0;
      var disp="";
      if (document.getElementById('temps-confirmation')) {
        var idtimer =setInterval('affichetemps("Le compte a déjà été validé. Vous allez être redirigé à la page de connexion dans ")',1000);
        var temps = "confirmation";
      }else if (document.getElementById('temps-modif-profil')) {
        var idtimer =setInterval('affichetemps("La page va se recharger automatiquement dans ")',1000);
        var temps = "profil";
      }
      
       
      function affichetemps(message){
        tps-- ;
        s = parseInt((tps%3600)%60) ;
        disp = message + (s<10 ? s : s) + " secondes." ;
        if (temps == "confirmation") {
          document.getElementById('temps-confirmation').innerHTML= disp;
        }else if (temps == "profil") {
          document.getElementById('temps-modif-profil').innerHTML= disp;
        }

        if ((s = 0)) {
         clearInterval(idtimer);
         return;
         }
      }