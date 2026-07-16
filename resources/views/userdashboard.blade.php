{{-- resources/views/userdashboard.blade.php --}}
@include('layout.header')
@include('layout.navbar')

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">User Dashboard</a>

        <div class="dropdown ms-auto">
            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->full_name ?? Auth::user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#">Change Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Main Content --}}
<div class="container mt-5 pt-5">
    <div class="card shadow-lg p-4">
        <div class="text-center mb-4">
            <h3>
                Welcome, {{ Auth::user()->full_name ?? Auth::user()->name }}
                @if(Auth::user()->dob)
                    <small class="text-muted">(DOB: {{ \Carbon\Carbon::parse(Auth::user()->dob)->format('d M Y') }})</small>
                @endif
            </h3>
            <p class="text-muted">Manage your profile details below</p>

            {{-- Profile Picture --}}
            <div class="position-relative d-inline-block">
                <img id="profilePreview"
                     src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-avatar.png') }}"
                     alt="Profile Picture"
                     class="rounded-circle border"
                     width="120" height="120">
                <label for="profile_picture" class="btn btn-sm btn-dark position-absolute bottom-0 end-0 rounded-circle">
                    <i class="bi bi-camera"></i>
                </label>
                <input type="file" id="profile_picture" name="profile_picture" class="d-none">
            </div>
        </div>

        {{-- Alert Messages --}}
        <div id="alertContainer"></div>

        {{-- Profile Update Form --}}
        <form id="updateProfileForm" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                {{-- Full Name --}}
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <div class="input-group">
                        <input type="text" name="full_name" class="form-control"
                               value="{{ Auth::user()->full_name }}">
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label class="form-label">Email (Non-editable)</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                </div>

                {{-- Age --}}
                <div class="col-md-6">
                    <label class="form-label">Age</label>
                    <input type="number" name="age" class="form-control" value="{{ Auth::user()->age }}">
                </div>

                {{-- Date of Birth --}}
                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" value="{{ Auth::user()->dob }}">
                </div>
            </div>

            {{-- Permanent Address --}}
            <div class="mt-4">
                <h5>Permanent Address</h5>
                @php
                    $permanent = Auth::user()->addresses->where('type', 'permanent')->first();
                @endphp
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Address Line 1</label>
                        <input type="text" name="permanent_address_line1" class="form-control"
                               value="{{ $permanent->address_line1 ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label>City</label>
                        <input type="text" name="permanent_city" class="form-control"
                               value="{{ $permanent->city ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Current Address --}}
            <div class="mt-4">
                <h5>Current Address</h5>
                @php
                    $current = Auth::user()->addresses->where('type', 'current')->first();
                @endphp
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Address Line 1</label>
                        <input type="text" name="current_address_line1" class="form-control"
                               value="{{ $current->address_line1 ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label>City</label>
                        <input type="text" name="current_city" class="form-control"
                               value="{{ $current->city ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Qualifications --}}
            <div class="mt-5">
                <h5>Qualifications</h5>
                <div id="qualificationSection">
                    @foreach(Auth::user()->qualifications as $q)
                        <div class="row g-2 mb-2 qualification-item">
                            <div class="col-md-4">
                                <input type="text" name="qualifications[][degree]" class="form-control"
                                       value="{{ $q->degree }}" placeholder="Degree">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="qualifications[][institute]" class="form-control"
                                       value="{{ $q->institute }}" placeholder="Institute">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="qualifications[][year]" class="form-control"
                                       value="{{ $q->year }}" placeholder="Year">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-danger btn-sm remove-qualification">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-primary btn-sm mt-2" id="addQualification">+ Add Qualification</button>
            </div>

            {{-- Experience --}}
            <div class="mt-5">
                <h5>Experience</h5>
                <div id="experienceSection">
                    @foreach(Auth::user()->experiences as $exp)
                        <div class="row g-2 mb-2 experience-item">
                            <div class="col-md-4">
                                <input type="text" name="experiences[][company_name]" class="form-control"
                                       value="{{ $exp->company_name }}" placeholder="Company">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="experiences[][role]" class="form-control"
                                       value="{{ $exp->role }}" placeholder="Role">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="experiences[][years]" class="form-control"
                                       value="{{ $exp->years }}" placeholder="Years">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-danger btn-sm remove-experience">×</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-primary btn-sm mt-2" id="addExperience">+ Add Experience</button>
            </div>

            {{-- Submit --}}
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-5">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@include('layout.footer')

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {

    // === Profile Picture Preview ===
    $("#profile_picture").on("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => $("#profilePreview").attr("src", e.target.result);
            reader.readAsDataURL(file);
        }
    });

    // === Add / Remove Qualification ===
    $("#addQualification").click(function() {
        $("#qualificationSection").append(`
            <div class="row g-2 mb-2 qualification-item">
                <div class="col-md-4"><input type="text" name="qualifications[][degree]" class="form-control" placeholder="Degree"></div>
                <div class="col-md-4"><input type="text" name="qualifications[][institute]" class="form-control" placeholder="Institute"></div>
                <div class="col-md-3"><input type="text" name="qualifications[][year]" class="form-control" placeholder="Year"></div>
                <div class="col-md-1 text-end"><button type="button" class="btn btn-danger btn-sm remove-qualification">×</button></div>
            </div>
        `);
    });

    $(document).on("click", ".remove-qualification", function() {
        $(this).closest(".qualification-item").remove();
    });

    // === Add / Remove Experience ===
    $("#addExperience").click(function() {
        $("#experienceSection").append(`
            <div class="row g-2 mb-2 experience-item">
                <div class="col-md-4"><input type="text" name="experiences[][company_name]" class="form-control" placeholder="Company"></div>
                <div class="col-md-4"><input type="text" name="experiences[][role]" class="form-control" placeholder="Role"></div>
                <div class="col-md-3"><input type="text" name="experiences[][years]" class="form-control" placeholder="Years"></div>
                <div class="col-md-1 text-end"><button type="button" class="btn btn-danger btn-sm remove-experience">×</button></div>
            </div>
        `);
    });

    $(document).on("click", ".remove-experience", function() {
        $(this).closest(".experience-item").remove();
    });

    // === AJAX Form Submit ===
    $("#updateProfileForm").submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('user.updateProfile') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showAlert('success', 'Profile updated successfully!');
            },
            error: function(xhr) {
                showAlert('danger', 'Error updating profile: ' + xhr.status + ' ' + xhr.statusText);
                console.log(xhr.responseText);
            }
        });
    });

    // === Reusable Alert ===
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $("#alertContainer").html(alertHtml);
        setTimeout(() => $(".alert").alert('close'), 4000);
    }
});
</script>

<style>
.card { border-radius: 20px; }
.navbar { box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
#profilePreview { object-fit: cover; }
</style>
