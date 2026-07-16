@include('layout.header')

<div class="container mt-4">
    <h3 class="mb-4">Employee Details</h3>

    <div class="card p-4 shadow">

        {{-- ✅ Profile Picture --}}
        @if($user->profile_picture)
            <div class="text-center mb-3">
                <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                     alt="Profile Picture" 
                     class="rounded-circle shadow"
                     width="150" height="150"
                     style="object-fit: cover;">
            </div>
        @else
            <div class="text-center mb-3">
                <img src="{{ asset('images/default-avatar.png') }}" 
                     alt="Default Avatar" 
                     class="rounded-circle shadow"
                     width="150" height="150"
                     style="object-fit: cover;">
            </div>
        @endif

        <h5 class="text-center">{{ $user->full_name }}</h5>
        <p class="text-center text-muted">{{ ucfirst($user->role ?? 'user') }}</p>

        <hr>

        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Age:</strong> {{ $user->age ?? 'N/A' }}</p>
        <p><strong>Date of Birth:</strong> {{ $user->dob ?? 'N/A' }}</p>

        <hr>

        {{-- ✅ Qualifications --}}
        <h5>Qualifications</h5>
        @if($user->qualifications->isNotEmpty())
            <ul>
                @foreach($user->qualifications as $q)
                    <li>{{ $q->degree }} - {{ $q->institute }} ({{ $q->year }})</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No qualifications found.</p>
        @endif

        <hr>

        {{-- ✅ Experiences --}}
        <h5>Experience</h5>
        @if($user->experiences->isNotEmpty())
            <ul>
                @foreach($user->experiences as $exp)
                    <li>{{ $exp->company_name }} - {{ $exp->role }} ({{ $exp->years }} years)</li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No experience found.</p>
        @endif

        <hr>

        {{-- ✅ Addresses --}}
        <h5>Addresses</h5>
        @if($user->addresses->isNotEmpty())
            <ul>
                @foreach($user->addresses as $addr)
                    <li><strong>{{ ucfirst($addr->type) }}:</strong> 
                        {{ $addr->address_line1 }}, {{ $addr->city }}, {{ $addr->state }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No addresses found.</p>
        @endif

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">← Back to Dashboard</a>
    </div>
</div>
