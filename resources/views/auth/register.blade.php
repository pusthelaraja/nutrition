@extends('layouts.frontend')

@section('title', 'Register - ' . config('app.name'))

@section('content')
<!-- Enhanced Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-white-50">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">
                <i class="fas fa-user-plus me-1"></i>Register
            </li>
        </ol>
    </div>
</nav>

<!-- Register Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark mb-2">
                                <i class="fas fa-user-plus text-primary me-2"></i>Create Account
                            </h3>
                            <p class="text-muted">Join us and start your healthy nutrition journey</p>
                        </div>

                        <!-- Register Form -->
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row g-3">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label fw-bold text-dark">
                                        <i class="fas fa-user text-primary me-1"></i>First Name
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('first_name') is-invalid @enderror"
                                           id="first_name"
                                           name="first_name"
                                           value="{{ old('first_name') }}"
                                           placeholder="Enter first name"
                                           required
                                           autocomplete="given-name"
                                           autofocus>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-bold text-dark">
                                        <i class="fas fa-user text-primary me-1"></i>Last Name
                                    </label>
                                    <input type="text"
                                           class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                                           id="last_name"
                                           name="last_name"
                                           value="{{ old('last_name') }}"
                                           placeholder="Enter last name"
                                           required
                                           autocomplete="family-name">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-12">
                                    <label for="email" class="form-label fw-bold text-dark">
                                        <i class="fas fa-envelope text-primary me-1"></i>Email Address
                                    </label>
                                    <input type="email"
                                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="Enter your email"
                                           required
                                           autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-12">
                                    <label for="phone" class="form-label fw-bold text-dark">
                                        <i class="fas fa-phone text-primary me-1"></i>Phone Number
                                    </label>
                                    <input type="tel"
                                           class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="Enter your phone number"
                                           required
                                           autocomplete="tel">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold text-dark">
                                        <i class="fas fa-lock text-primary me-1"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               placeholder="Enter password"
                                               required
                                               autocomplete="new-password">
                                        <button class="btn btn-outline-secondary"
                                                type="button"
                                                onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold text-dark">
                                        <i class="fas fa-lock text-primary me-1"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control form-control-lg"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               placeholder="Confirm password"
                                               required
                                               autocomplete="new-password">
                                        <button class="btn btn-outline-secondary"
                                                type="button"
                                                onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="passwordConfirmationToggleIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label fw-bold text-dark">
                                        <i class="fas fa-calendar text-primary me-1"></i>Date of Birth
                                    </label>
                                    <input type="date"
                                           class="form-control form-control-lg @error('date_of_birth') is-invalid @enderror"
                                           id="date_of_birth"
                                           name="date_of_birth"
                                           value="{{ old('date_of_birth') }}"
                                           autocomplete="bday">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-bold text-dark">
                                        <i class="fas fa-venus-mars text-primary me-1"></i>Gender
                                    </label>
                                    <select class="form-select form-select-lg @error('gender') is-invalid @enderror"
                                            id="gender"
                                            name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mt-4">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="terms"
                                       required>
                                <label class="form-check-label text-muted" for="terms">
                                    I agree to the <a href="#" class="text-primary">Terms and Conditions</a>
                                    and <a href="#" class="text-primary">Privacy Policy</a>
                                </label>
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>

                            <!-- Divider -->
                            <div class="text-center mt-4">
                                <hr class="my-4">
                                <span class="text-muted bg-white px-3">or</span>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="text-muted mb-0">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">
                                        Sign In
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="card mt-4 border-0 bg-light">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3 text-center">
                            <i class="fas fa-star text-primary me-1"></i>Why Create an Account?
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4 text-center">
                                <i class="fas fa-shopping-cart text-primary fs-4 mb-2"></i>
                                <h6 class="fw-bold text-dark">Easy Checkout</h6>
                                <p class="text-muted small">Save your details for faster checkout</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-history text-primary fs-4 mb-2"></i>
                                <h6 class="fw-bold text-dark">Order History</h6>
                                <p class="text-muted small">Track all your previous orders</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-heart text-primary fs-4 mb-2"></i>
                                <h6 class="fw-bold text-dark">Wishlist</h6>
                                <p class="text-muted small">Save your favorite products</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(fieldId + 'ToggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strength = getPasswordStrength(password);
    updatePasswordStrengthIndicator(strength);
});

function getPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}

function updatePasswordStrengthIndicator(strength) {
    const indicator = document.getElementById('passwordStrengthIndicator');
    if (!indicator) {
        const passwordField = document.getElementById('password').parentElement;
        const strengthDiv = document.createElement('div');
        strengthDiv.id = 'passwordStrengthIndicator';
        strengthDiv.className = 'mt-2';
        passwordField.appendChild(strengthDiv);
    }

    const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    const strengthColors = ['danger', 'warning', 'info', 'success', 'success'];

    document.getElementById('passwordStrengthIndicator').innerHTML = `
        <div class="progress" style="height: 5px;">
            <div class="progress-bar bg-${strengthColors[strength]}"
                 style="width: ${(strength / 5) * 100}%"></div>
        </div>
        <small class="text-${strengthColors[strength]}">${strengthText[strength]}</small>
    `;
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    confirmPassword.addEventListener('input', function() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Passwords do not match');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });
});
</script>

<style>
.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
}
</style>
@endsection
