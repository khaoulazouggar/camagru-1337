// The buttons to start & stop stream and to capture the image
var btnStart = document.getElementById( "btn-start" );
var btnCapture = document.getElementById("btn-capture");
var btnsave = document.getElementById( "btn-save" );
var check = false;

// The video stream
btnStart.addEventListener( "click", function(){
    check = true;
    var video = document.getElementById("stream");
    
    navigator.getMedia = navigator.getUserMedia ||
    navigator.webkitGetUserMedia ||
    navigator.mozGetUserMedia ||
    navigator.msGetUserMedia;
    
    navigator.getMedia({
    video: true,
    audio: false
    }, function(stream){
        video.srcObject = stream;
        video.play()
    }, function(error){
    
    });
}
);
 
// Capture canvas
btnCapture.addEventListener("click", function()
{
  if (document.getElementById('imgfilter').src != ""){
    var canvas = document.getElementById("canvas");  
    var ctx = canvas.getContext( '2d' );
    if(canvas.width != 400 || canvas.height != 300)
    {
      window.location.reload(true);
    }
    ctx.drawImage( stream, 0, 0, canvas.width, canvas.height );
    document.getElementById('clear').disabled = false;
  }else
    {
      alert("Choose a sticker");
    }
}
);

// stickers
var emoticon;
var imgfilter = document.getElementById('imgfilter');
var filter = document.getElementsByName('filter');

for (var j= 0; j <= filter.length -1; j++)
{

  filter[j].onclick = function(event) {
    if(canvas.toDataURL() !== document.getElementById('canvas2').toDataURL())
    {
      emoticon = this.value;
      imgfilter.src = emoticon;
    }
    if(check){
      imgfilter.style.display = 'block';
      emoticon = this.value;
      imgfilter.src = emoticon;
    }
  }
}

// saveimage
btnsave.addEventListener( "click", function(){  
 if(canvas.toDataURL() !== document.getElementById('canvas2').toDataURL()) {
   var canvasData = canvas.toDataURL("image/png");
   var params = "imgData="+canvasData+"&filtrsticker="+emoticon;
   var ajax = new XMLHttpRequest();

   ajax.open("POST", "http://localhost/Camagru/Posts/SaveImage");
   ajax.withCredentials = true;
   ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   ajax.onreadystatechange = function()
    {
      if (this.readyState == 4 && this.status == 200)
      { 
        
      }
    }
    ajax.send(params);
    window.location.reload(true);
 }
}
);

/////////////////////////upload///////////////////////////
function el(file){return document.getElementById(file);}

var canvas  = el("canvas");
var context = canvas.getContext("2d");

function readImage() {
    if ( this.files && this.files[0] ) {
        var FR= new FileReader();
        FR.onload = function(e) {
           var img = new Image();
           img.addEventListener("load", function() {
             context.clearRect(0, 0, canvas.width, canvas.height);
             context.drawImage(img, 0, 0, canvas.width, canvas.height);
           });
           img.src = e.target.result;
        };       
        FR.readAsDataURL( this.files[0] );
        document.getElementById('clear').disabled = false;
    }
}

el("file").addEventListener("change", readImage, false);

// clear
document.getElementById('clear').addEventListener('click', function(){
  context.clearRect(0, 0, canvas.width, canvas.height);
});
