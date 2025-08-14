{{-- resources/views/auth/login.blade.php --}}
<form method="POST" action="{{ route('login.post') }}">
  @csrf
  <input type="email" name="email" placeholder="Email" required />
  <input type="password" name="password" placeholder="Password" required />
  <button type="submit">Sign in</button>
</form>
