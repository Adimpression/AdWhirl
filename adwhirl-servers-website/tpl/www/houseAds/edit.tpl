<div id ="main" class="content">
  <div id="mainContent" class="mainLeft">
    <form id="infoForm" action="/houseAds/ad/editSubmit" enctype="multipart/form-data" method="post">
      
      <h3> Information </h3>
      <fieldSet class="mainForm">
      <input type="hidden" name="cid" value="{$houseAd->id}" class="text"/>
      <p class="formElement required ">
        <label for="name">Name</label>
        <input type="text" name="name" value="{$houseAd->name}" />
      </p>        
      <p class="formElement required ">
        <label for="description">Text</label>
        <input type="text" name="description" value="{$houseAd->description}" />
      </p>
      </fieldset>
      <h3> Creative </h3>
      <fieldSet class="mainForm">
        <p class="formElement required ">
          <label for="type">Type</label>
          <input type="text" name="type" value="{$houseAd->type}" />
        </p>
        <p class="formElement required ">
          <label for="link">Link</label>
          <input type="text" name="link" value="{$houseAd->link}" />
        </p>
        <p class="formElement required ">
          <label for="linkType">Link Type</label>
          <input type="text" name="linkType" value="{$houseAd->linkType}" />
        </p>
        <p class="formElement required ">
          <label for="image">Image:</label>
          <input type="file" name="image" />
          <img src="{$houseAd->imageLink}"/>
          <input type="hidden" name="oldImageLink" value="{$houseAd->imageLink}" />
        </p>
      </fieldset>
      <div id="buttons">
         <span class="button"><a id="save" href='#'><span>Save</span></a></span>             
      </div>
    </form>
  </div>
  <div id="preview" class="right">
    preview
  </div>
</div>
  
<script>
{literal}
$(document).ready(function() {
  $('#save').bind("click",
     function(e) {
       $("#infoForm").submit();
     });
});
{/literal}
</script>






