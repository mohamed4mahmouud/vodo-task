@extends('layouts.app')

@section('content')
    <h2 class="text-center">Login</h2>
    <form id="loginForm" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input id="username" type="text" class="form-control" name="username" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>
        </div>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            fetch('{{ route("login") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ username: username, password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    // Save the token in local storage
                    localStorage.setItem('authToken', data.token);
                    alert('Login successful!');

                    // Redirect or update UI after successful login
                    window.location.href = '/notes';
                } else {
                    alert('Login failed: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
