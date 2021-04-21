var previewFile = function (event) {
   var output = document.getElementById('banner');
   output.src = URL.createObjectURL(event.target.files[0]);
   output.onload = function () {
      URL.revokeObjectURL(output.src) // free memory
   }
};
var previewFile2 = function (event) {
   var output = document.getElementById('e-banner');
   output.src = URL.createObjectURL(event.target.files[0]);
   output.onload = function () {
      URL.revokeObjectURL(output.src) // free memory
   }
};

$(document).ready(function () {

   // add banner
   $("#add-banner-modal #add-banner-btn").click(function (e) {
      e.preventDefault();

      // loader animation to button
      $(this).prop('disabled', true);
      var _temp = this;
      $(_temp).html('Adding banner <i class="fa fa-spinner fa-spin ml-1"></i>');

      var formData = new FormData();
      formData.append('bannerurl', $('#add-banner-modal #banner-url').val());
      formData.append('bannerImage', $('#add-banner-modal #activityBanner')[0].files[0]);
      formData.append('addBanner', "addBanner");

      // ajax function
      $.ajax({
         enctype: 'multipart/form-data',
         url: "./php/extras.php",
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         dataType: 'json',
         cache: false,
         success: function (response) {
            console.log(response);

            if (response.bannerurlErr) {
               $("#add-banner-modal #banner-url-error").html(response.bannerurlErr);
            } else {
               $("#add-banner-modal #banner-url-error").html("");
            }
            if (response.bannerImageErr) {
               $("#add-banner-modal #activityBanner-error").html(response.bannerImageErr);
            } else {
               $("#add-banner-modal #activityBanner-error").html("");
            }

            if (response.success == true) {
               $("#add-banner-form").trigger("reset");
               $("#add-banner-modal #banner").attr("src", "./assets/banner.jpg");
               $("#add-banner-modal").modal("hide");
               readBanner();
               notification('Heads up!', 'Banner Added Successfully.', 'success');
            }

            $(_temp).removeAttr("disabled");
            $(_temp).html('Add banner');
         },
         error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
               message = jqXHR.responseText;
            }
            console.log(message);
            $(_temp).removeAttr("disabled");
            $(_temp).html('Add banner');
         }
      });
   })
   // add banner
   $("#edit-banner-modal #edit-banner-btn").click(function (e) {
      e.preventDefault();

      // loader animation to button
      $(this).prop('disabled', true);
      var _temp = this;
      $(_temp).html('Editing banner <i class="fa fa-spinner fa-spin ml-1"></i>');

      var formData = new FormData();
      formData.append('bannerurl', $('#edit-banner-modal #e-banner-url').val());
      formData.append('bannerImage', $('#edit-banner-modal #e-activityBanner')[0].files[0]);
      formData.append('editbannerid', $('#edit-banner-modal #hiddenid').val());
      formData.append('editBanner', "editBanner");

      // ajax function
      $.ajax({
         enctype: 'multipart/form-data',
         url: "./php/extras.php",
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         dataType: 'json',
         cache: false,
         success: function (response) {
            console.log(response);

            if (response.bannerurlErr) {
               $("#edit-banner-modal #e-banner-url-error").html(response.bannerurlErr);
            } else {
               $("#edit-banner-modal #e-banner-url-error").html("");
            }
            if (response.bannerImageErr) {
               $("#edit-banner-modal #e-activityBanner-error").html(response.bannerImageErr);
            } else {
               $("#edit-banner-modal #e-activityBanner-error").html("");
            }

            if (response.success == true) {
               $("#edit-banner-form").trigger("reset");
               $("#edit-banner-modal #banner").attr("src", "./assets/banner.jpg");
               $("#edit-banner-modal").modal("hide");
               readBanner();
               notification('Heads up!', 'Banner Edited Successfully.', 'success');
            }

            $(_temp).removeAttr("disabled");
            $(_temp).html('Add banner');
         },
         error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
               message = jqXHR.responseText;
            }
            console.log(message);
            $(_temp).removeAttr("disabled");
            $(_temp).html('Edit banner');
         }
      });
   })
})

function readBanner() {
   var readrecord = 'readrecord';
   $.ajax({    //create an ajax request to display.php
      type: "POST",
      url: "./php/extras.php",
      data: {
         readrecord: readrecord
      },
      dataType: "html",   //expect html to be returned                
      success: function (response) {
         $("#responsecontainer").html(response);
         $('#myTable').DataTable();
      }
   });
}

function DeleteBanner(deleteid) {


   var confirmation = confirm("Are you sure about deleting the banner? You will not be able to access it again. Click ok to continue");

   if (confirmation == true) {
      //buttons for disable & spinner class
      var button = "#myTable .activity-" + deleteid;
      var _temp = "#delete" + deleteid;
      $(button).prop('disabled', true);
      $(_temp).html("<i class='fas fa-spinner fa-spin'></i>");

      $.ajax({
         type: "POST",
         url: "./php/extras.php",
         data: {
            deleteid: deleteid
         },
         success: function (response) {
            if (response == 'success') {
               notification('Heads up!', 'Banner deleted', 'success');
               readBanner();
            }
         },
         error: function () {
            notification('Ooops...', 'Some error on server side', 'error');
            // enable buttons & remove spinner
            $(button).prop('disabled', false);
            $(_temp).html("<i class='far fa-trash'></i>");
         }
      });
   }
}

function EditBanner(editid) {
   $("#edit-banner-modal #loading").css('display', 'flex');
   $("#edit-banner-modal .info-block").css('display', 'none');
   $("#edit-banner-form").trigger('reset');
   $.ajax({
      type: "POST",
      url: "./php/extras.php",
      data: {
         editid: editid
      },
      dataType: 'json',
      success: function (response) {
         // console.log(response);

         $("#edit-banner-modal #e-banner").attr('src', response.banner.substring(3));
         $("#edit-banner-modal #e-banner-url").val(response.url);
         $("#edit-banner-modal #hiddenid").val(response.id);

         $("#edit-banner-modal #loading").css('display', 'none');
         $("#edit-banner-modal .info-block").css('display', 'flex');
      },
      error: function (jqXHR, textStatus, errorThrown) {
         var message = errorThrown;
         if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
            message = jqXHR.responseText;
         }
         // console.log(message);
         notification('Ooops...', message, 'error');
      }
   });
}