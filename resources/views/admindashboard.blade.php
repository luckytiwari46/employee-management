@include('layout.header')
<div class="container mt-4">

    {{-- ✅ Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->full_name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile.show') }}" class="nav-link text-light">
                            <i class="bi bi-person-lines-fill"></i> change password 
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger ms-2">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ✅ Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ✅ Users Table --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white fw-bold">
            Registered Employees
        </div>
        <div class="card-body">
            @if($users->isEmpty())
                <p class="text-center text-muted">No users found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Profile</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Qualifications</th>
                                <th>Role</th>
                                <th>Registered On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($user->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" width="50" height="50" class="rounded-circle">
                                        @else
                                            <img src="{{ asset('default-avatar.png') }}" alt="Profile" width="50" height="50" class="rounded-circle">
                                        @endif
                                    </td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->qualifications->isNotEmpty())
                                            <ul class="mb-0">
                                                @foreach($user->qualifications as $q)
                                                    <li>{{ $q->degree }} ({{ $q->year }})</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted">No qualifications</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($user->role ?? 'user') }}</td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.user.details', $user->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
