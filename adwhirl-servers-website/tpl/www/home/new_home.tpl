<link href="old_css/style_home.css?nocache=1" rel="stylesheet" type="text/css" />

<p id="promo">New to mediation? Learn more <a href="http://helpcenter.adwhirl.com/content/general-questions">here</a></p>

<div id="splash">
  <h1>The Leading Mediation Solution for App Developers</h1>
  <h2>Maximize revenue from your iPhone or Android application</h2>
  <h2>Free, Transparent and Completely Open Source</h2>
</div>

<div id="features">
  <div class="sectionHeader sectionHeaderActive">New from AdWhirl</div>
  <ul class="homeBullet">
    <li>Use unlimited ad networks of your choice</li>
    <li>AdWhirl now supports iAd</li>
    <li>Major improvements to House Ad functionality</li>
    <li>Brand new powerful and intuitive user interface</li>
  </ul>
  <a class="bigBlueButton" href="/home/register">Get Started</a>
</div>

<div id="login">
  <div class="sectionHeader sectionHeaderActive">Log In</div>
  <form method="post" action="/home/login/login">
    <label for="email">Email Address:</label>
    <input name="email" type="text" id="email" tabindex="1"/>

    <label for="password">Password: <a href="/home/login/forgotPassword">(Forgot Password?)</a></label>
    <input name="password" type="password" id="password" onfocus="formInUse = true;" tabindex="2" />

    <input id="submit" name="login" type="submit" class="button" value="Submit" tabindex="3" />{if $invalidlogin}<span class="error">&nbsp;&nbsp;Invalid login. Please try again.</span>{/if}
    <br />
    <div id="register">Not Registered? <a href="/home/register">Sign Up Now</a></div>
  </form>
</div>
