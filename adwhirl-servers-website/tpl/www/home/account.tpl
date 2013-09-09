<div id="content">

  <div id="subtitleBox">
    Account Settings
  </div>

  <form id="editAccount" name="editAccount" enctype="multipart/form-data" method="post" action="/home/account/update" onsubmit="return checkform(this);">
    <input type="hidden" name="returnPage" value="{$returnPage}" class="text"/>
 <div id="appInfo" class="section">
            <div class="sectionHeader sectionHeaderActive">
            Info

            </div>
            <div class="sectionBody">
              <input type="hidden" name="aid" value="{$app->id}" />
  
              <p class="formElement required ">
                <label for="firstName">First:</label>
                <input type="text" name="firstName" value="{$user->firstName}" class="text"/>
              </p>
              <p class="formElement required ">
                <label for="lastName">Last:</label>
                <input type="text" name="lastName" value="{$user->lastName}" class="text"/>
              </p>
              <p class="formElement required ">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="{$user->email}" class="text"/>
              </p>
              <span style="padding-left:173px;height:30px;display:inline-block"><a id="changePassword" href="#">Change Password</a></span>
            </div>
          </div>
          
         <div id="serverInfo" class="section">
            <div class="sectionHeader sectionHeaderActive">
              Delete Account
            </div>
              <p class="formElement required ">
                <label>Delete Account:</label>
                 <span class="button"><a href="/home/account/delete"><span>Delete</span></a></span>
                 <span style="padding-left: 10px;">You'll be asked one more time to confirm.</span>
              </p>
        </div>


  </form>

  <div style="text-align:center">
    
  <hr/>
  <span class="button disabled"><a href="#" id="save" class="disabled"><span>Save Changes</span></a></span>
  <a href="" class="cancel disabled">Cancel</a>

  </div>
  
  <div id="passwordModal" class="hidden">
      <form id="passwordForm">
        <input type="hidden" name="email" value="{$user->email}"/>
  	<div class="modalTop center">
  	    <span class="modalHeader" style="align:left">Change Password</span>
  	    <span style="color:#333">
			 <p class="formElement" style="width:100%">
          <label style="width:103px;" for="oldpassword">Old Password:</label>
          <input style="width:105px" id = "oldpassword" type="password" name="oldpassword" value="" class="text"/>
        </p>  	    
 			 <p class="formElement" style="width:100%">
          <label style="width:103px;" for="password">New Password:</label>
          <input style="width:105px" id="password" type="password" name="password" value="" class="text"/>
        </p>
 			 <p class="formElement" style="width:100%">
          <label style="width:103px;" for="confirmpassword">Confirm Password:</label>
          <input style="width:105px;" type="password" name="confirmpassword" value="" class="text"/>
        </p>
        </span>
  		<hr>
  		<span class="button"><a id="OK" href="#"><span>OK</span></a></span>
  		<span class="button"><a href="#" id="cancelChangePassword"><span>Cancel</span></a></span>
  	</div>
  	<div class="modalBottom"></div>
  	</form>
  </div>
  
    
</div>

<script language="JavaScript" type="text/javascript">
var currentEmail = "{$user->email}";
{literal}


$(document).ready(function() {
  $("#email").change(function() {
    $('#warnEmail').remove();
    if ($(this).val()!=currentEmail)
      $(this).parent().append("<span id='warnEmail'' class='error'> After you changed your email. You will have to use the new email to login.</span>");
  });
  $("#changePassword").click(function(e) {
    $(".msg").remove();
		e.preventDefault();
		$("#passwordModal").modal({
			opacity:80,
			overlayCss: {backgroundColor:"#fff"}
		});			
	});
  
	$("#editAccount").validate({
			rules: {
				firstName: "required",
				lastName: "required",
				email: {
					required: true,
					email: true,
					remote: '/home/login/isValidLogin'
				},
				agreeeToTOS: "required"
			},
			messages: {
				firstName: "Please enter your firstname",
				lastName: "Please enter your lastname",
				email: {
				  required: "Please enter a valid email address",
				  email: "Please enter a valid email address",
				  remote: "The email was already registered"
				}
			}
		});
  $("#cancelChangePassword").click(function(e) {
    e.preventDefault();
    $("#changePassword").parent().append($("<span class='msg' style='padding-left:66px'> Your password has not been changed.</span>"));
    $.modal.close();
  });
  $("#passwordForm").validate({
      submitHandler: function(form) {
        var data = {'email':currentEmail,'password':$('#oldpassword').val(),'new_password':$('#password').val()};
        
        $.post('/home/login/changePassword', data, function(data) {
          $("#changePassword").parent().append($("<span class='msg' style='padding-left:66px'> Your password has "+  (data=='true'?'':'not ') +"been changed.</span>"));
          $("#changePassword").remove();
          $.modal.close();          
        });
        return false;
      },    
			rules: {
			  oldpassword: {
			    required:true,			    
					remote: {					  
            url: "/home/login/checkPassword",
            type: "post",
            data: {
              email: function() { return $("#email").val();},
              password: function() { return $("#oldpassword").val();}
            }            
				  }
        },
			  password: {
					required: true,
					minlength: 5,
				},
				confirmpassword: {
					required: true,
					minlength: 5,
					equalTo: "#password",
				}
			},
			messages: {
			  oldpassword: {
			    required: "Please provide your old password",
			    remote: "Your password is incorrect"
			  },
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				confirmpassword: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"					
				}
			}
		});
  $('#save').click(function(e) {
    e.preventDefault();
    if (!$(this).is(".disabled")) {		
      $("#editAccount").submit();
    }
  });
  $('#OK').bind('click', function(e) {
    e.preventDefault();
    if (!$(this).is(".disabled")) {		
      $('#passwordForm').submit();
    }
  });       
  $('#editAccount input').bind("keypress change", function(e) {
    $("#save").removeClass("disabled").parent().removeClass("disabled");
    $(".cancel").removeClass("disabled");
  });  
  $('#passwordForm input').bind("keypress change", function(e) {
    $("#OK").removeClass("disabled").parent().removeClass("disabled");
  });  
  $('#save').click,(function(e) {
    e.preventDefault();
		if (!$(this).is(".disabled"))
       		$("#editAccount").submit();
     });
  $('.cancel').bind("click", function(e) {
    e.preventDefault();
		if (!$(this).is(".disabled")) {
		  window.location = window.location;
		}
  });
});

{/literal}

</script>
