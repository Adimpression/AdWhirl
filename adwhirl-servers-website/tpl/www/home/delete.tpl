
Are you sure you want to delete this account?<br />
<br />
email: {$user->email}<br />
<br />
<form action="/home/account/deleteConfirmed" method="post">
<input type="hidden" name="email" value="{$user->email}" />
<input type="submit" value="delete" />
</form>
