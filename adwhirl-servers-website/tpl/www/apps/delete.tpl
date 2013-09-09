
Are you sure you want to delete this app?<br />
<br />
Name: {$app->name}<br />
<br />
<form action="/apps/oneApp/deleteSubmit" method="post">
<input type="hidden" name="aid" value="{$app->id}" />
<input type="submit" value="delete" />
</form>
