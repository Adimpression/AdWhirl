<div id="dev">
  <div id="subtitleBox">Developer's Resources</div>

  <p>We've developed open source mediation solution so that advertisers and publishers have an open, transparent ad serving option. The <a href="http://code.google.com/p/adwhirl/" target="_blank">open source</a> solution is available for all iPhone and Android app developers and advertising networks.</p>

  <div id="dev_main">
    <div class="sectionHeader sectionHeaderActive">AdWhirl SDK and Server</div>
    <p>The AdWhirl Open Source Client SDK contains the code for your iPhone or Android application to display ads from different ad networks*.</p>

    <p class="bold">Latest AdWhirl SDK</p>
    <ul>
      <li><a href="http://code.google.com/p/adwhirl/downloads/list" target="_blank">Download the latest version</a></li>
      <li><a href="/pdf/AdWhirl_OpenSourceSDK_Setup_Instructions-Android.pdf">Instructions for Android</a></li>
      <li><a href="/pdf/AdWhirlSDKInstructionsforiPhone.pdf">Instructions for iPhone</a></li>
    </ul>

    <p class="bold">Options for implementing the mediation server:</p>
    <div id="adwhirl_hosted">
      <p>
        <b>AdWhirl Hosted Solution</b><br />
        AdWhirl hosts the server and configuration website for configuring traffic allocation of the ad networks included in your apps.
      </p>
    </div>
    <div id="self_hosted">
      <p>
        <b>Self Hosted</b><br />
        If you prefer to host your own configuration server and website, we've released the source code for both.
      </p>
      <ul>
        <li><a href="http://code.google.com/p/adwhirl/source/list?repo=servers" target="_blank">Code repository</a></li>
        <li><a href="/pdf/AdWhirl_OpenSourceServer_Setup_Instructions.pdf">Amazon Web Services Instructions</a></li>
      </ul>
    </div>

    <p>*You must have and integrate the SDK's for the ad networks that you plan to include in your application.</p>
  </div>

  <div id="dev_side">
    <div class="section">
      <div class="sectionHeader sectionHeaderActive">Discuss</div>
      <p class="bold">Connect with other users</p>
      <ul>
        <li><a href="http://groups.google.com/group/adwhirl-users" target="_blank">User Forum</a></li>
        <li><a href="http://code.google.com/p/adwhirl/issues/list" target="_blank">Report Issues</a></li>
      </ul>
    </div>

    <div class="section">
      <div class="sectionHeader sectionHeaderActive">Contribute</div>
      <p>The source code and all files are hosted on Google Code along with discussion forums and other community tools.</p>
      <p class="bold">Help make AdWhirl better</p>
      <ul>
        <li><a href="http://code.google.com/p/adwhirl/source/list?repo=sdk" target="_blank">Source for the SDK</a></li>
        <li><a href="http://code.google.com/p/adwhirl/source/list?repo=servers" target="_blank">Source for the Server</a></li>
        <li><a href="http://code.google.com/p/adwhirl/w/list" target="_blank">Wiki</a></li>
      </ul>
    </div>

    <div class="section">
      <div class="sectionHeader sectionHeaderActive">Ad Networks</div>
      <p>AdWhirl lets you use as many networks as you like. Below you will find a small sample of some of the advertising solutions you can choose to integrate in your application, through AdWhirl.</p>
      <select id="chooseNetwork" name="network">
      {html_options options=$network_options}
      </select>
      <span class="button">
        <a id="networkButton" href="#" class="button">
          <span>Visit</span>
        </a>
      </span>
      <p>This list of networks is not comprehensive and is displayed for informational purposes only. AdMob does not endorse any of these solutions, nor can it guarantee that the list is current.</p>
      <p>If you are an Ad Network and would like to be added to this list, please contact <a href="mailto:support@adwhirl.com">support@adwhirl.com</a></p>
    </div>
  </div>
</div>

<script>
{literal}
$(document).ready(function() {
	$("#networkButton").click(function() {
		window.open($("#chooseNetwork").val(),'_newtab');
	});
});
{/literal}
</script>