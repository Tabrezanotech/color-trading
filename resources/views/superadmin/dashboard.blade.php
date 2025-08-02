<!DOCTYPE html>
<html>
<head><title>Super Admin Dashboard</title></head>
<body>
    <h2>Welcome, Super Admin!</h2>
     <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
   
</body>
</html>
