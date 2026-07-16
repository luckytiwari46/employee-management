@include('layout.header')
@include('layout.navbar')

<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow p-4 rounded-4">
        <h3 class="text-center mb-4">Login</h3>

        {{-- Success or Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary rounded-pill">Login</button>
            </div>

            <div class="text-center">
                <small>Don't have an account? 
                    <a href="{{ route('signup') }}" class="text-decoration-none">Create Account</a>
                </small>
            </div>
        </form>
    </div>
</div>

@include('layout.footer')
