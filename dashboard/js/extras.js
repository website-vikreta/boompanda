var previewFile = function (event) {
   var output = document.getElementById('banner');
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
               // readActivities();
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
})