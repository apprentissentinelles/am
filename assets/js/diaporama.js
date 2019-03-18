var slideIndex = 1;
var diapositives = document.querySelectorAll(".diapositive");
var nb_diapos = diapositives.length;
var timer = setInterval(carousel, 4500);

function carousel() { 
    showDivs(slideIndex);
    if (slideIndex === 1)
        setSlideShowDetails();
    if (slideIndex === nb_diapos) {
        slideIndex = 1;
    } else {
        slideIndex++;
    }

}

function plusDivs(n) {
    showDivs(slideIndex += n);
}

function currentDiv(n) {
    showDivs(slideIndex = n);
}

function showDivs(n) {
    var i;
    var x = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    if (n > x.length) { slideIndex = 1; }
    if (n < 1) { slideIndex = x.length; }
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";  
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" w3-white", "");
    }
    x[slideIndex-1].style.display = "block";  
    dots[slideIndex-1].className += " w3-white";
}

var max = (a, b) => a > b ? a : b;
var min = (a, b) => a < b ? a : b;

function setSlideShowDetails() {

    for (let i=0; i<nb_diapos; i++) {
        let currentHeight = parseInt(getComputedStyle(diapositives[i], 'height'));
        currentHeight = currentHeight > diapositives[i].clientHeight ? currentHeight : diapositives[i].clientHeight;

        let currentWidth = parseInt(getComputedStyle(diapositives[i], 'width'));
        currentWidth = currentWidth > diapositives[i].clientWidth ? currentWidth : diapositives[i].clientWidth;

        let imageOrientation = diapositives[i].naturalWidth > diapositives[i].naturalHeight ? "LANDSCAPE" : "PORTRAIT";
        let viewportOrientation = window.innerWidth > window.innerHeight ? "LANDSCAPE" : "PORTRAIT";

        diapositives[i].style.maxWidth = diapositives[i].naturalWidth + "px";
        /*console.log("currentWidth:" + currentWidth);
        console.log("currentHeight:" + currentHeight);
        console.log("window.innerWidth:" + window.innerWidth);
        console.log("window.innerHeight:" + window.innerHeight);
        console.log("diapositives[i].naturalWidth:" + diapositives[i].naturalWidth);
        console.log("diapositives[i].naturalHeight:" + diapositives[i].naturalHeight);*/

        if (imageOrientation === "LANDSCAPE" && viewportOrientation === "LANDSCAPE") {
            if (diapositives[i].naturalWidth > window.innerWidth) {
                let rapportWidth = (diapositives[i].naturalWidth - window.innerWidth) / diapositives[i].naturalWidth;
                rapportWidth /= 1.2;
                rapportWidth *= 100;
                diapositives[i].style.left = "-" + Math.floor(rapportWidth) + "vw";
                diapositives[i].style.top = "-" + Math.floor(rapportWidth *0.25) + "vh";
            } else {
                diapositives[i].style.left = "calc(100vw - " + max(window.innerWidth, currentWidth) + "px)";
                diapositives[i].style.top = "0";
            }
            diapositives[i].style.width = diapositives[i].naturalWidth + "px";
            diapositives[i].style.height = diapositives[i].naturalHeight + "px";
        }
        else if (imageOrientation === "LANDSCAPE" && viewportOrientation === "PORTRAIT") { 
            if (diapositives[i].naturalWidth > window.innerWidth) {
                let rapportWidth = (diapositives[i].naturalWidth - window.innerWidth) / diapositives[i].naturalWidth;
                rapportWidth /= 0.8;
                rapportWidth *= 100;
                diapositives[i].style.left = "-" + Math.floor(rapportWidth) + "vw";
                diapositives[i].style.top = "-2vh";
                diapositives[i].style.width = "192vh";
                diapositives[i].style.height = "80vh";
            } else {
                diapositives[i].style.left = "calc(100vw - " + max(window.innerWidth, currentWidth) + "px)";
                diapositives[i].style.top = "0";
                diapositives[i].style.width = diapositives[i].naturalWidth + "px";
                diapositives[i].style.height = diapositives[i].naturalHeight + "px";
            }
        }
    }
}

window.onresize = function() {
    setSlideShowDetails();
}

window.onload = function() {
    let l = document.querySelectorAll(".diapositive").length;
    let html = "";
    for (let i=0; i<l; i++) {
        html += '<span class="w3-badge demo w3-border" onclick="currentDiv('+ (i + 1) +')"></span>';
    }
    document.querySelector(".dots-container").innerHTML = html;
    setSlideShowDetails();
}