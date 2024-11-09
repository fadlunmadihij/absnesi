<!-- resources/views/auth/passwords/verify-otp.blade.php -->

<form action="{{ route('password.verify') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ $email }}">

    <div>
        <label for="code">Enter OTP</label>
        <input type="text" name="code" id="code" required>
    </div>

    <div>
        <label for="password">New Password</label>
        <input type="password" name="password" id="password" required>
    </div>

    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>
    </div>

    <button type="submit">Submit</button>
</form>
