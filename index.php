
<!DOCTYPE>  
<html>  
<head>
<style>


 
 .box1 {
            background-color: lightgreen;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
           margin-top:5px;
            
        }
        .box2 {
            background-color: white;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
               margin-bottom:5px;
        }
 
       
 
 
        
 
.button {
    border-radius: 30%;
  background-color: #003e59;
  border: none;
  color: white;
  padding: 8px 18px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
  margin: 4px 2px;
  cursor: pointer;
   border: 2px solid white;

}
.button:hover {
 border: 2px solid #003e59;
  box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);

}
.button:active {
  background-color: white;
  color: #003e59;
 border: 2px solid #003e59;
  box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);

}

.loader {
  border: 6px solid #f3f3f3;
  border-radius: 50%;
  border-top: 6px solid #3498db;
  width: 10px;
  height: 10px;
  -webkit-animation: spin 0.3s linear infinite; /* Safari */
  animation: spin 0.3s linear infinite;
}



/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

</head>
<title>Photo Reducer</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
     
     var adLoaded=false;
     var imageOriginalFileSizeBytes,toBeCompressedFileSizeBytes=0,imageOriginalName,imageUploadedName,finalCompressedImageName;
      $("#displayImage").hide();
      $("#compressInput").hide();
            $("#pleaseWait").hide();
      $("#compressResult").hide();
     $("#downloadResult").hide();
     $(".loader").hide();
 document.getElementById("hideTillLoad").style.visibility = "visible"; 


      
      
      
      function imageUploadStart() {
                
               // alert(navigator.onLine);
if(!navigator.onLine)
{
    alert("Please connect to the internet");
}else{
                var fd = new FormData();
                var files = $('#fileToUpload')[0].files;
               
                
                
       if(files.length > 0 ){
            fd.append('fileToUpload', files[0]);
           $.ajax({
                    url: 'upload.php',
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function(result1){
                        $("#imageUploadResponse").html("Uploading...");
                                             $(".loader").show();
    
                        $('#startCompress').prop('disabled', true);

                    },
                    
                    
 success: function (result2) {
                                        $('#startCompress').prop('disabled', false);
                                $(".loader").hide();

 $("#imageUploadResponse").show();
     $("#uploadedImageSize").show();
      $("#compressResult").hide();
        $("#downloadResult").hide();
     
                     if(result2.includes("done/")){
                         
       
          $('#imageUploadResponse').html("Image uploaded successfully.");
          const myArray = result2.split("/"); // retreiving file size,name etc from result
          imageOriginalFileSizeBytes=parseInt(myArray[1]);// converting text to int
          imageOriginalName=myArray[2];//original name of the image chosen
          imageUploadedName=myArray[3];//uploaded name in server

                    $('#uploadedImageSize').html("Image File Size: "+(imageOriginalFileSizeBytes/1024).toFixed(2)+" KB or "+(imageOriginalFileSizeBytes/(1024*1024)).toFixed(3)+" MB");

           $("#compressInput").show();

    displayImage.src = URL.createObjectURL(files[0]);
$("#displayImage").show();
    
    
                   }else
                    {
                                 $('#imageUploadResponse').html(result2);
                                $("#fileToUpload").val('');

                          }



                   }
    
                
                
                
                });
           
       }else
       {
           alert("Please choose an image to upload!!");
       }
                
                
            }
            }
            
              $("#startCompress").click(function(e) {
                                     

                
                  toBeCompressedFileSizeBytes=parseFloat($("#compressToSize").val());// entered value now not in bytes

                    if(toBeCompressedFileSizeBytes>0){
 
                          // convert entered value to bytes
                    if($("#kbormb").val()==="kb"){
                      toBeCompressedFileSizeBytes=toBeCompressedFileSizeBytes*1024;
                     }else
                     {
                      toBeCompressedFileSizeBytes=toBeCompressedFileSizeBytes*1024*1024;
                     }
                  
 
                        if(toBeCompressedFileSizeBytes>=imageOriginalFileSizeBytes)
                        {
                        alert("Please enter a value less than the original file size");

                        }else
                        {
                            //START COMPRESS HERE
                            
                            
                            if(!navigator.onLine)
                              {
                              alert("Please connect to the internet");
                              }
                              else{
                              
                            $("#pleaseWait").show();
                              $(".loader").show();

                            $("#compressResult").hide();
                                      $("#downloadResult").hide();
                         $('#startCompress').prop('disabled', true);
 
                       
                              $.post("compress.php",
                              {
                                 "imageUploadedName": imageUploadedName,
                                  "toBeCompressedFileSizeBytes": toBeCompressedFileSizeBytes
                              },
                             function(data, status){
                                                             $("#pleaseWait").hide();
                                                                  $(".loader").hide();

                                       $('#startCompress').prop('disabled', false);
 
                                if(status=="success"){
                                          $("#compressResult").show();
                                     
                              
                            
                              
                              // split the result to display and save final photo name
                              const compressResultArray = data.split("/");
                        
                            if(compressResultArray[0]==="success"){
                                
                                 $("#downloadResult").show();
                                 finalCompressedImageName=compressResultArray[2];
                                 const element = document.getElementById("downloadResult");
                                  element.scrollIntoView();

                            }else if(compressResultArray[0]==="failed1")
                            {
                                 $("#downloadResult").hide();
                             const element = document.getElementById("compressResult");
                                  element.scrollIntoView();

                            }else if(compressResultArray[0]==="failed2")
                            {
                              $("#downloadResult").show();
                                 finalCompressedImageName=compressResultArray[2];
                                 const element = document.getElementById("downloadResult");
                                  element.scrollIntoView();
    
                                
                            }
                        
                        
                              
                                    $("#compressResult").html(compressResultArray[1]);
                                   
                                     
                                }
                              });
                        }
                    }
                
                  
                  
                    }
                    else{
                        alert("Please enter a valid file size value");
                    }
                
              });
            
             $("#downloadResult").click(function(e) {
                  if(!navigator.onLine)
                              {
                              alert("Please connect to the internet");
                              }
                 
                              else
                 
                               {
                 const nameArray = imageOriginalName.split("."); 
                 
                   $.post("compressedFileCheck.php",
                              {
                                 "finalCompressedImageName": finalCompressedImageName 
                            
                              },
                             function(data, status){
                                  if(status=="success"){
                                    
                                      if(data=="true"){
                                window.location = "download.php?img="+finalCompressedImageName+"&name="+nameArray[0]+".jpg";  
      
                
                                      }
                                      else
                                      {
                                     alert("The image expired. Please compress again and download.");     
                                      }
                                      
                                  }
                            
                             });
                 

             }
                
        });
           
           
            $('input[type="file"]').change(function(e){
                var sFile = e.target.files[0].type;
                if(sFile.includes("image/png")||sFile.includes("image/jpg")||sFile.includes("image/jpeg"))
                {
                    setTimeout(function(){
imageUploadStart();



    },1); 
                }else
                {
                 alert("Please choose a JPG or PNG file");   
                   $("#fileToUpload").val('');
                    $("#compressInput").hide();
      $("#compressResult").hide();
     $("#downloadResult").hide();
     $("#imageUploadResponse").hide();
     $("#uploadedImageSize").hide();
                }
                
            });
           
         
         
         
         

// mobile or desktop check
window.mobileCheck = function() {
  let check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
};
     
function adjustLayout(){
    
    
    
    if(mobileCheck()){
        //is mobile true
        document.getElementById("wholePage").style.padding = "10px 2px 50px 1px";
      

    }else
    {
        //is mobile false
        document.getElementById("wholePage").style.padding = "5px 2px 50px 1px";
        
        
    }
}     


        adjustLayout(); 
      
         
         
           
                
        });
</script>

<body>  

<header style="position: fixed; left: 0;top: 0;width: 100%;background-color: #003e59;color: #003e59;text-align: center;height:10px">
</header>








<div id="wholePage" style="padding-bottom:50px;">
<div id="hideTillLoad" style="visibility: hidden;">

<center>
    
    <div class="box2">
 <h2 style="color: #003e59;">Photo Reducer</h2> 
 </div>
 
 <div class="box1">
 <h3 style="color: #003e59;">Compress image size in KB or MB</h3> 
 </div>
 
<br>
 <img id="displayImage" src="favicon.png" alt="Uploaded Photo" height="100px"> <br>
  Select image to compress:<br><br>
  <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" >
 
<br><br><br>
<div id="imageUploadResponse" >
</div>
<br>
<div id="uploadedImageSize" style="color: #0000ff;">
</div>

<div id="compressInput">
 
    <br> <br>
    Compress to file size below:
    <input type="text" id="compressToSize" name="compressToSize" minlength="1" maxlength="10" size="5"> 
    <select id="kbormb" name="kbormb">
    <option value="kb"> KB </option>
    <option value="mb"> MB </option>
  </select>
 
  <br><br>
    <input type="submit" id="startCompress" value="Compress Image" name="startCompress" class="button">

  
  
  <br><br>

</div>
   
<div id="pleaseWait"  style="color: #0000ff; ">
    Please Wait
</div>
  <br><br>
  
 <strong> <div id="compressResult" style="color: #ff0000; ">Compressed file size
</div>
</strong>
  <br>


    <input type="submit" id="downloadResult" value="Download" name="downloadResult" class="button" >
  
  
  
</center>
</div>

  <center>
 <div class="loader"></div>

</center>




 
        
</div>


        
        
    
        

 





<footer style="position: fixed; left: 0;bottom: 0;width: 100%;background-color: #003e59;color: #ffffff;text-align: right;height:24px;line-height:24px;box-shadow: 0px -1px #adadad;opacity: 0.9;">
    
</footer>

</body>  
</html>  

