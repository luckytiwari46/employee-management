@include('layout.header')
@include('layout.navbar')

<div class="container mt-5 mb-5">
  <h2 class="text-center mb-4">Sign Up</h2>

  {{-- Success Message --}}
  @if(session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
  @endif

  {{-- Error Message --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Signup Form --}}
  <form method="POST" action="{{ route('signup.submit') }}" enctype="multipart/form-data" id="signupForm" novalidate>
    @csrf

    {{-- Full Name --}}
    <div class="mb-3">
      <label>Full Name</label>
      <input name="full_name" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror" required>
      @error('full_name')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
      <label>Email</label>
      <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
      @error('email')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>
    
    {{-- Date of Birth --}}
    <div class="mb-3">
    <label>Date of Birth</label>
    <input type="date" name="dob" value="{{ old('dob') }}" class="form-control @error('dob') is-invalid @enderror" required>
    @error('dob')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
    </div>

    {{-- Password --}}
    <div class="mb-3">
      <label>Password</label>
      <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
      <small class="text-muted">Min 6 characters, must contain both letters & numbers.</small>
      <div id="passwordError" class="text-danger small mt-1"></div>
      @error('password')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>

    {{-- Confirm Password --}}
    <div class="mb-3">
      <label>Confirm Password</label>
      <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
      <div id="confirmPasswordError" class="text-danger small mt-1"></div>
    </div>

    {{-- Age --}}
    <div class="mb-3">
      <label>Age</label>
      <input name="age" type="number" value="{{ old('age') }}" class="form-control @error('age') is-invalid @enderror" required>
      @error('age')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>

    {{-- Profile Picture --}}
    <div class="mb-3">
      <label>Profile Picture</label>
      <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror">
      @error('profile_picture')
        <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>

    {{-- Qualifications --}}
    <h5>Qualifications</h5>
    <div id="qualifications">
      <div class="row mb-2">
        <div class="col"><input type="text" name="qualifications[0][degree]" class="form-control" placeholder="Degree" required></div>
        <div class="col"><input type="text" name="qualifications[0][institute]" class="form-control" placeholder="Institute"></div>
        <div class="col"><input type="text" name="qualifications[0][year]" class="form-control" placeholder="Year"></div>
        <div class="col-auto"><button type="button" class="btn btn-success addQualification">+</button></div>
      </div>
    </div>

    {{-- Experiences --}}
    <h5>Experiences</h5>
    <div id="experiences">
      <div class="row mb-2">
        <div class="col"><input type="text" name="experiences[0][company_name]" class="form-control" placeholder="Company" required></div>
        <div class="col"><input type="text" name="experiences[0][role]" class="form-control" placeholder="Role"></div>
        <div class="col"><input type="text" name="experiences[0][years]" class="form-control" placeholder="Years"></div>
        <div class="col-auto"><button type="button" class="btn btn-success addExperience">+</button></div>
      </div>
    </div>

    {{-- Permanent Address --}}
    <h5>Permanent Address</h5>
    <div class="row mb-2">
      <div class="col"><input name="permanent_address_line1" class="form-control" placeholder="Address Line 1" required></div>
      <div class="col"><input name="permanent_address_line2" class="form-control" placeholder="Address Line 2"></div>
    </div>
    <div class="row mb-3">
      <div class="col"><input name="permanent_city" class="form-control" placeholder="City" required></div>
      <div class="col">
        <select name="permanent_state" class="form-control" required>
          <option value="">Select State</option>
          @foreach($states as $state)
          <option value="{{ $state }}">{{ $state }}</option>
          @endforeach
        </select>
      </div>
    </div>

    {{-- Current Address --}}
    <h5>Current Address</h5>
    <div class="row mb-2">
      <div class="col"><input name="current_address_line1" class="form-control" placeholder="Address Line 1" required></div>
      <div class="col"><input name="current_address_line2" class="form-control" placeholder="Address Line 2"></div>
    </div>
    <div class="row mb-3">
      <div class="col"><input name="current_city" class="form-control" placeholder="City" required></div>
      <div class="col">
        <select name="current_state" class="form-control" required>
          <option value="">Select State</option>
          @foreach($states as $state)
          <option value="{{ $state }}">{{ $state }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary px-4">Sign Up</button>
    </div>
  </form>
</div>

<script>
// === Add/Remove Qualifications ===
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('addQualification')) {
    let index = document.querySelectorAll('#qualifications .row').length;
    let html = `<div class="row mb-2">
      <div class="col"><input type="text" name="qualifications[${index}][degree]" class="form-control" placeholder="Degree" required></div>
      <div class="col"><input type="text" name="qualifications[${index}][institute]" class="form-control" placeholder="Institute"></div>
      <div class="col"><input type="text" name="qualifications[${index}][year]" class="form-control" placeholder="Year"></div>
      <div class="col-auto"><button type="button" class="btn btn-danger removeQualification">-</button></div>
    </div>`;
    document.getElementById('qualifications').insertAdjacentHTML('beforeend', html);
  }

  if (e.target.classList.contains('removeQualification')) {
    e.target.closest('.row').remove();
  }

  if (e.target.classList.contains('addExperience')) {
    let index = document.querySelectorAll('#experiences .row').length;
    let html = `<div class="row mb-2">
      <div class="col"><input type="text" name="experiences[${index}][company_name]" class="form-control" placeholder="Company" required></div>
      <div class="col"><input type="text" name="experiences[${index}][role]" class="form-control" placeholder="Role"></div>
      <div class="col"><input type="text" name="experiences[${index}][years]" class="form-control" placeholder="Years"></div>
      <div class="col-auto"><button type="button" class="btn btn-danger removeExperience">-</button></div>
    </div>`;
    document.getElementById('experiences').insertAdjacentHTML('beforeend', html);
  }

  if (e.target.classList.contains('removeExperience')) {
    e.target.closest('.row').remove();
  }
});

// === Client-side password validation ===
document.getElementById('signupForm').addEventListener('submit', function(e) {
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('password_confirmation').value;
  const passError = document.getElementById('passwordError');
  const confirmError = document.getElementById('confirmPasswordError');

  passError.textContent = '';
  confirmError.textContent = '';

  const regex = /^(?=.*[A-Za-z])(?=.*\d).{6,}$/;
  if (!regex.test(password)) {
    e.preventDefault();
    passError.textContent = 'Password must be at least 6 characters and contain both letters and numbers.';
  }

  if (password !== confirmPassword) {
    e.preventDefault();
    confirmError.textContent = 'Passwords do not match.';
  }
});
</script>

@include('layout.footer')
