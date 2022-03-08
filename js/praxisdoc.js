//remove alert after 5 sec
window.setTimeout(function() {
	$(".alert-autoremove").fadeTo(500, 0).slideUp(500, function(){
		$(this).remove(); 
	});
}, 5000);

// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$(document).ready(function(){
  $("#mybtn").click(function(){
    $("#myDiv").show();
      setTimeout(function(){$("#myDiv").hide();},3000);
  });
});

function ValidateSize(fileToUpload,UploadButton) {
    var file = document.getElementById(fileToUpload); 
    var fileSize = file.files[0].size / 1024 / 1024; // in MiB
    if (fileSize > 1) {
        document.getElementById('sizeExceeded').style.display="block";
        document.getElementById(UploadButton).disabled=true;
    } else {
        document.getElementById(UploadButton).disabled=false;
        document.getElementById('sizeExceeded').style.display="none";
    }
}  