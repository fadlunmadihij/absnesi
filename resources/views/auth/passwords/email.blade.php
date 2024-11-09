<form action="{{ route('password.sendOtp') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email_or_phone">Masukkan Email atau Nomor Telepon</label>
        <input type="text" name="email_or_phone" id="email_or_phone" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Kirim OTP</button>
</form>
