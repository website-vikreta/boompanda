$('#add-account-modal input:radio[name="accountType"]').change(function () {
   $("#add-account-modal .wrapper").addClass('d-none');
   if ($(this).is(':checked') && $(this).val() == 'bank') {
      $("#add-account-modal .bank-wrapper").removeClass('d-none');
   } else if ($(this).is(':checked') && $(this).val() == 'vpa') {
      $("#add-account-modal .vpa-wrapper").removeClass('d-none');
   }
});

$(document).ready(function () {
   $("#create-contact-modal #create-contact-btn").click(function (e) {
      e.preventDefault();

      // disable button & add spinner class
      $(this).prop('disabled', true);
      var _temp = this;
      $(_temp).html('Creating contact <i class="fa fa-spinner fa-spin"></i>');

      formData = new FormData();
      formData.append('name', $("#create-contact-modal #name").val());
      formData.append('mobile', $("#create-contact-modal #mobile").val());
      formData.append('create_contact', true);

      $.ajax({
         enctype: 'multipart/form-data',
         url: "./php/accounts.php",
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         dataType: 'json',
         cache: false,
         success: function (response) {
            // console.log(response);
            if (response.nameErr) {
               $("#create-contact-modal #name-error").html(response.nameErr);
            } else {
               $("#create-contact-modal #name-error").html("");
            }
            if (response.mobileErr) {
               $("#create-contact-modal #mobile-error").html(response.mobileErr);
            } else {
               $("#create-contact-modal #mobile-error").html("");
            }
            if (response.serverErr) {
               notification('Oops...', response.serverErr, 'success');
            }
            if (response.success == true) {
               notification('Heads up!', 'The contact has been created.', 'success');
               $('#create-contact-modal').modal("hide");
            }

            $(_temp).removeAttr("disabled");
            $(_temp).html('Create Contact');
         },
         error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
               message = jqXHR.responseText;
            }
            // console.log(message);
            $(_temp).removeAttr("disabled");
            $(_temp).html('Create Contact');
         }
      });
   })

   $("#add-account-modal #add-account-btn").click(function (e) {
      e.preventDefault();
      e.preventDefault();

      // disable button & add spinner class
      $(this).prop('disabled', true);
      var _temp = this;
      $(_temp).html('Adding Beneficiary <i class="fa fa-spinner fa-spin"></i>');

      formData = new FormData();
      formData.append('accountType', $("#add-account-modal input[name=accountType]:checked").val());
      // for account
      formData.append('acc-name', $("#add-account-modal #beneficiary-name").val());
      formData.append('acc-number', $("#add-account-modal #acc-number").val());
      formData.append('c-acc-number', $("#add-account-modal #c-acc-number").val());
      formData.append('ifsc', $("#add-account-modal #ifsc").val());
      // for vpa
      formData.append('vpa', $("#add-account-modal #vpa").val());
      formData.append('add_beneficiary', true);

      $.ajax({
         enctype: 'multipart/form-data',
         url: "./php/accounts.php",
         type: "POST",
         data: formData,
         processData: false,
         contentType: false,
         dataType: 'json',
         cache: false,
         success: function (response) {
            // console.log(response);
            if (response.nameErr) {
               $("#add-account-modal #name-error").html(response.nameErr);
            } else {
               $("#add-account-modal #name-error").html("");
            }
            if (response.accErr) {
               $("#add-account-modal #acc-error").html(response.accErr);
            } else {
               $("#add-account-modal #acc-error").html("");
            }
            if (response.c_accErr) {
               $("#add-account-modal #c-acc-error").html(response.c_accErr);
            } else {
               $("#add-account-modal #c-acc-error").html("");
            }
            if (response.ifscErr) {
               $("#add-account-modal #ifsc-error").html(response.ifscErr);
            } else {
               $("#add-account-modal #ifsc-error").html("");
            }
            if (response.vpaErr) {
               $("#add-account-modal #vpa-error").html(response.vpaErr);
            } else {
               $("#add-account-modal #vpa-error").html("");
            }
            if (response.serverErr) {
               notification('Oops...', response.serverErr, 'success');
            }
            if (response.success == true) {
               notification('Heads up!', 'Beneficiary added successfully', 'success');
               $('#add-account-form').trigger("reset");
               $('#add-account-modal').modal("hide");
               $("#add-account-modal .wrapper").addClass('d-none');
               $("#add-account-modal .bank-wrapper").removeClass('d-none');
               readAccounts();
            }

            $(_temp).removeAttr("disabled");
            $(_temp).html('Add Beneficiary');
         },
         error: function (jqXHR, textStatus, errorThrown) {
            var message = errorThrown;
            if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
               message = jqXHR.responseText;
            }
            // console.log(message);
            $(_temp).removeAttr("disabled");
            $(_temp).html('Add Beneficiary');
         }
      });
   })
})

function viewContact() {
   $("#create-contact-modal #loading").css('display', 'flex');
   $("#create-contact-modal .info-block").css('display', 'none');
   $.ajax({
      url: './php/accounts.php',
      type: "GET",
      data: {
         contact: "contact"
      },
      dataType: 'json',
      success: function (data) {
         $("#create-contact-modal #name").val(data.name);
         $("#create-contact-modal #email").val(data.email);
         $("#create-contact-modal #mobile").val(data.mobile_number);

         // remove loader & show form
         $("#create-contact-modal #loading").css('display', 'none');
         $("#create-contact-modal .info-block").css('display', 'flex');
      },
      error: function (jqXHR, textStatus, errorThrown) {
         var message = errorThrown;
         if (jqXHR.responseText !== null && jqXHR.responseText !== 'undefined' && jqXHR.responseText !== '') {
            message = jqXHR.responseText;
         }
         console.log(message);
      }
   })
}

function readAccounts() {
   var readrecord = 'readrecord';
   $.ajax({    //create an ajax request to display.php
      type: "POST",
      url: "./php/accounts.php",
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

function MakePrimary(approveid) {
   //buttons for disable & spinner class
   var button = "#myTable .user-" + approveid;
   var _temp = "#approve" + approveid;
   $(button).prop('disabled', true);
   $(_temp).html("Make Parimary <i class='fas fa-spinner fa-spin'></i>");

   $.ajax({
      type: "POST",
      url: "./php/accounts.php",
      data: {
         approveid: approveid
      },
      success: function (response) {
         if (response == 'success') {
            notification('Heads up!', 'Your account is set to primary account.', 'success');
            readAccounts();
         }
         // enable buttons & remove spinner
         $(button).prop('disabled', false);
         $(_temp).html("Make Primary");
      },
      error: function () {
         notification('Ooops...', 'Some error on server side', 'error');
         // enable buttons & remove spinner
         $("#myTable .user-" + approveid).prop('disabled', false);
         $(_temp).html("Make Primary");
      }
   });
}