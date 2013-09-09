<div id="content">
<form id="editAccount" name="editAccount" enctype="multipart/form-data" method="post" action="/home/register/registerProcessed" onsubmit="return checkform(this);">
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
                <input id="email" type="text" name="email" value="{$user->email}" class="text"/>
              </p>
  						 <p class="formElement required ">
		              <label for="password">Password:</label>
		              <input id="password" type="password" name="password" value="" class="text"/>
		            </p>
  						 <p class="formElement required ">
		              <label for="confirmpassword">Confirm Password:</label>
		              <input type="password" name="confirmpassword" value="" class="text"/>
		            </p>		
		            <p class="formElement required ">
		              <label>&nbsp;</label>
		              <input id="agreeeToTOS" name="agreeeToTOS" type="checkbox" tabindex="5" />
		              I agree to the following:
		            </p>
		            <p class="formElement required ">
		              <label>&nbsp;</label>

		                                        <textarea readonly="readonly" rows="5" cols="50" name="tos">AdMob Terms Of Service
		                                        PLEASE READ THIS USER AGREEMENT ("AGREEMENT") CAREFULLY BEFORE USING THE SERVICES OFFERED BY ADMOB, INC. ("COMPANY"). BY CLICKING THE "SUBMIT" BOX, YOU AGREE TO BECOME BOUND BY THE TERMS AND CONDITIONS OF THIS AGREEMENT. IF YOU DO NOT AGREE TO ALL THE TERMS AND CONDITIONS OF THIS AGREEMENT, CLICK ON THE "CANCEL" BUTTON AND YOU WILL NOT HAVE ANY RIGHT TO USE THE SERVICES OFFERED BY COMPANY. COMPANY'S ACCEPTANCE IS EXPRESSLY CONDITIONED UPON YOUR ASSENT TO ALL THE TERMS AND CONDITIONS OF THIS AGREEMENT, TO THE EXCLUSION OF ALL OTHER TERMS; IF THESE TERMS AND CONDITIONS ARE CONSIDERED AN OFFER BY COMPANY, ACCEPTANCE IS EXPRESSLY LIMITED TO THESE TERMS.
		                                        DISCLAIMERS
		                                        User acknowledges and agrees that Company has no special relationship with or fiduciary duty to User and that Company has no control over, and no duty to take any action regarding: which users gains access to the Site or Services; what Content User accesses or receives via the Site or Services; what Content other Users may make available, publish or promote in connection with the Services; what effects any Content may have on User or its users or customers; how User or its users or customers may interpret, view or use the Content; what actions User or its users or customers may take as a result of having been exposed to the Content, or whether Content is being displayed properly in connection with the Services.
		                                        Further, (i) if User is a publisher, User specifically acknowledges and agrees that Company has no control over (and is merely a passive conduit with respect to) any Content that may be submitted or published by any advertiser, and that User is solely responsible (and assumes all liability and risk) for determining whether or not such Content is appropriate or acceptable to User, and (ii) if User is an advertiser, User specifically acknowledges and agrees that Company has no control over any Content that may be available or published on any publisher website (or otherwise), and that User is solely responsible (and assumes all liability and risk) for determining whether or not such Content is appropriate or acceptable to User.
		                                        User releases Company from all liability in any way relating to User's acquisition (or failure to acquire), provision, use or other activity with respect to Content in connection with the Site or Services. The Site may contain, or direct User to sites containing, information that some people may find offensive or inappropriate. Company makes no representations concerning any Content contained in or accessed through the Site or Services, and Company will not be responsible or liable for the accuracy, copyright compliance, legality or decency of material contained in or accessed through the Site or Services. Company makes no guarantee regarding the level of impressions of or clicks on any Advertisement, or the timing of delivery of such impressions and/or clicks.
		                                        THE SERVICES, CONTENT AND SITE ARE PROVIDED ON AN "AS IS" BASIS, WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE OR NON-INFRINGEMENT. COMPANY DOES NOT WARRANT THE RESULTS OF USE OF THE SERVICES, INCLUDING, WITHOUT LIMITATION, THE RESULTS OF ANY ADVERTISING CAMPAIGN, AND USER ASSUMES ALL RISK AND RESPONSIBILITY WITH RESPECT THERETO. SOME STATES DO NOT ALLOW LIMITATIONS ON HOW LONG AN IMPLIED WARRANTY LASTS, SO THE ABOVE LIMITATIONS MAY NOT APPLY TO USER.
		                                        Electronic Communications Privacy Act Notice (18 USC 2701-2711): COMPANY MAKES NO GUARANTY OF CONFIDENTIALITY OR PRIVACY OF ANY COMMUNICATION OR INFORMATION TRANSMITTED ON THE SITE OR ANY WEBSITE LINKED TO THE SITE OR THROUGH ANY USE OF THE SERVICES. Company will not be liable for the privacy of e-mail addresses, phone or communication device numbers, registration and identification information, disk space, communications, confidential or trade-secret information, or any other Content stored on its equipment and transmitted over networks accessed by the Site, or otherwise connected with User's use of the Site or Services.
		                                        REGISTRATION AND SECURITY. As a condition to using Services, User may be required to register with Company and select a password and enter User's email address ("Company User ID"). User shall provide Company with accurate, complete, and updated registration information. Failure to do so shall constitute a breach of this Agreement, which may result in immediate termination of User's account. User may not (i) select or use as a Company User ID a name of another person with the intent to impersonate that person; (ii) use as a Company User ID a name subject to any rights of a person other than User without appropriate authorization. Company reserves the right to refuse registration of, or cancel a Company User ID in its discretion. User shall be responsible for maintaining the confidentiality of User's Company password.
		                                        EQUIPMENT AND ANCILLARY SERVICES. User shall be responsible for obtaining and maintaining any equipment or ancillary services needed to connect to, access the Site or otherwise use the Services, including, without limitation, hardware devices, software, and other Internet, wireless, broadband, phone or other communication device connection services. User shall be responsible for ensuring that such equipment or ancillary services are compatible with the Site and any Services and User shall be responsible for all charges incurred in connection with all such equipment and ancillary services, including any fees charged for airtime usage and/or sending and receiving messages or related notifications.
		                                        INDEMNITY. User will indemnify and hold Company, its parents, subsidiaries, affiliates, officers and employees, harmless, including costs and attorneys' fees, from any claim or demand made by any third party due to or arising out of User's access to the Site, use of the Services, the violation of this Agreement by User, or the infringement by User, or any third party using the User's account, of any intellectual property or other right of any person or entity.
		                                        LIMITATION OF LIABILITY. IN NO EVENT SHALL COMPANY BE LIABLE WITH RESPECT TO THE SITE OR THE SERVICES (I) FOR ANY AMOUNT IN THE AGGREGATE IN EXCESS OF THE FEES PAID BY USER THEREFOR; OR (II) FOR ANY INDIRECT, INCIDENTAL, PUNITIVE, OR CONSEQUENTIAL DAMAGES OF ANY KIND WHATSOEVER. SOME STATES DO NOT ALLOW THE EXCLUSION OR LIMITATION OF INCIDENTAL OR CONSEQUENTIAL DAMAGES, SO THE ABOVE LIMITATIONS AND EXCLUSIONS MAY NOT APPLY TO USER.
		                                        </p>
		                                        TERMINATION. Either party may terminate the Services at any time by notifying the other party by any means. Company may also terminate or suspend any and all Services and access to the Site immediately, without prior notice or liability, if User breaches any of the terms or conditions of this Agreement. Any fees paid hereunder are non-refundable and non-cancelable. Upon termination of the User's account, User's right to use the Services will immediately cease and User will remove all Company code from User's Mobile Properties. All provisions of this Agreement which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, and limitations of liability.
		                                    </textarea>

		                   </p>

		            </fieldSet>
		          </div>
		        </div>


		     </div>
		</form>
		<div style="text-align:center">
		<hr/>
		<span class="button"><a id="save" href='#'><span>Register</span></a></span>
		</div>
	
</div>




</div>


<script language="JavaScript" type="text/javascript">
{literal}

$(document).ready(function() {
	$.validator.addMethod(
	  "notEqualTo",
	  function(value, element, param) {
			return $(param).val()!=value;
	  },
	  "Please don't use your email as your password."
	);
	$("#editAccount").validate({
			rules: {
				firstName: "required",
				lastName: "required",
				password: {
					required: true,
					minlength: 5,
					notEqualTo: "#email"
				},
				confirmpassword: {
					required: true,
					minlength: 5,
					equalTo: "#password",
					notEqualTo: "#email"					
				},
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
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long"
				},
				confirmpassword: {
					required: "Please provide a password",
					minlength: "Your password must be at least 5 characters long",
					equalTo: "Please enter the same password as above"					
				},
				email: {
				 required: "Please enter a valid email address",
				 email: "Please enter a valid email address",
				 remote: "This email address was registered"
				},
				agreeeToTOS: "Please accept our policy"
			}
		});
  $('#save').bind("click",
     function(e) {
       	$("#editAccount").submit();
     });
});



</script>
{/literal}
