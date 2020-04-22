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
var idtimer =setInterval('affichetemps()',1000);
 
function affichetemps(){
  tps-- ;
  s = parseInt((tps%3600)%60) ;
  disp = "Le compte a déjà été validé. Vous allez être redirigé à la page de connexion dans " + (s<10 ? s : s) + " secondes." ;
  document.getElementById('temps').innerHTML= disp;
 
  if ((s = 0)) {
   clearInterval(idtimer);
   return;
   }
}