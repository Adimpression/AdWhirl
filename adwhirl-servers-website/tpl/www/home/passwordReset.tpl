<div class="info">
                                    <h2>Reset Password</h2>
                                    <div>
                                        Enter a new password.
                                    </div>
                                </div>
				<form action="/home/login/passwordResetProcessed" method="post">
				<input type="hidden" name="ufid" value="{$ufid}" />
                                <ul>
                                    <li id="foli1" class="   ">
                                        <label class="desc" id="title1" for="Field1">
                                            Password<span id="req_1" class="req">*</span>
                                        </label>
                                        <div>
                                            <input id="password" name="password" type="password" class="field text medium" value="" maxlength="255" tabindex="1" />
                                        </div>
                                    </li>
                                    <li class="buttons">
                                        <input id="saveForm" class="btTxt" type="submit" value="Submit" />
                                    </li>
</ul>
</form>
</div>