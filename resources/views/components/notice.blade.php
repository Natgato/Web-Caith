@if(session('success'))<div class="feedback success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="feedback error" role="alert" tabindex="-1"><p>Revisa los campos indicados:</p><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
