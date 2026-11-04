<label class="check-row"><input type="checkbox" name="privacy_consent" value="1" @checked(old('privacy_consent')) required><span>Autorizo el tratamiento de los datos que envío para atender esta solicitud y he leído la <a href="{{ route('privacy') }}">información de privacidad</a>. Puedo solicitar su retiro o eliminación.</span></label>
@error('privacy_consent')<p class="field-error">{{ $message }}</p>@enderror
<div class="honeypot" aria-hidden="true"><label for="website">Deja este campo vacío</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
