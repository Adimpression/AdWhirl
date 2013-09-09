<div class="info">
  <h2>Forgot Password Request</h2>
  <div>Please enter your email address and we'll send you an email where you can reset your password.</div>
</div>

<form id="loginForm" action="/home/login/forgotPasswordProcessed" method="post">
  <p class="formElement required ">
   <label for="email">Email:</label>
   <input id="email" type="text" name="email" value="{$user->email}" class="text"/>
   <span id="error" class="error"></span>
  </p>
  <p class="formElement required ">
	 <label>&nbsp;</label><span class="button"><a id="save" href='#'><span>Submit</span></a></span>
	</p>
</form>

<script>
{literal}

$(document).ready(function () {
	$("#save").click(function() {
		var msg = checkEmail($("#email").val());
		if (msg=="") {
		$.get('/home/login/isValidLogin',{'email':$("#email").val()},function(data) {
			if (data=='true') {
				$("#error").text("We can't find your account. Please try again.");
			} else {
				$("#loginForm").submit();
			}
		});
		} else {
			$("#error").text(msg);
		}
	});
});
{/literal}
</script>