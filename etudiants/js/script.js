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