Hi {{ explode(' ', $user->name)[0] }}!

<br><br>

Here is a link allowing you to reset your password:
<a href="{{ url('password/reset/'.$token) }}">{{ url('password/reset/'.$token) }}</a>

<br><br>

If you did not request a link to reset your password, please let us know.

<br><br>

Thanks!

<br>

{{ Spark::product() }}
