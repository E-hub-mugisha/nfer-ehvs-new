<!-- resources/views/employee/create-profile.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Create Employee Profile</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>

        :root{
            --navy:#0e2039;
            --gold:#d4943a;
            --gold-light:#e4ad59;
            --bg:#f4f7fb;
            --border:#e5e7eb;
        }

        body{
            background:linear-gradient(
                135deg,
                #0e2039 0%,
                #162d50 100%
            );
            min-height:100vh;
            font-family:'Inter',sans-serif;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px 15px;
        }

        .profile-card{
            width:100%;
            max-width:1000px;
            border:none;
            border-radius:24px;
            overflow:hidden;
            background:white;
            box-shadow:
                0 10px 40px rgba(0,0,0,0.15);
        }

        .left-panel{
            background:
                linear-gradient(
                    135deg,
                    rgba(14,32,57,0.97),
                    rgba(22,45,80,0.97)
                );
            color:white;
            padding:50px 40px;
            position:relative;
            overflow:hidden;
        }

        .left-panel::before{
            content:'';
            position:absolute;
            width:300px;
            height:300px;
            background:rgba(212,148,58,0.15);
            border-radius:50%;
            top:-80px;
            right:-80px;
        }

        .left-panel::after{
            content:'';
            position:absolute;
            width:180px;
            height:180px;
            background:rgba(212,148,58,0.08);
            border-radius:50%;
            bottom:-60px;
            left:-60px;
        }

        .brand{
            font-size:28px;
            font-weight:700;
            margin-bottom:40px;
            position:relative;
            z-index:2;
        }

        .brand span{
            color:var(--gold);
        }

        .hero-title{
            font-size:38px;
            font-weight:700;
            line-height:1.2;
            margin-bottom:20px;
            position:relative;
            z-index:2;
        }

        .hero-text{
            color:rgba(255,255,255,0.75);
            line-height:1.7;
            position:relative;
            z-index:2;
        }

        .feature-list{
            margin-top:40px;
            position:relative;
            z-index:2;
        }

        .feature-item{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:18px;
            color:rgba(255,255,255,0.88);
        }

        .feature-icon{
            width:42px;
            height:42px;
            border-radius:12px;
            background:rgba(212,148,58,0.15);
            color:var(--gold);
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:18px;
        }

        .right-panel{
            padding:45px;
            background:white;
        }

        .form-title{
            font-size:28px;
            font-weight:700;
            color:var(--navy);
            margin-bottom:8px;
        }

        .form-subtitle{
            color:#64748b;
            margin-bottom:35px;
        }

        .form-label{
            font-weight:600;
            color:var(--navy);
            margin-bottom:8px;
        }

        .form-control,
        .form-select{
            height:52px;
            border-radius:14px;
            border:1px solid #dbe2ea;
            padding:0 16px;
            font-size:14px;
            transition:all .25s ease;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:var(--gold);
            box-shadow:0 0 0 .2rem rgba(212,148,58,0.18);
        }

        .upload-box{
            border:2px dashed rgba(212,148,58,0.35);
            border-radius:18px;
            padding:30px;
            text-align:center;
            background:#fafbfd;
            transition:all .3s;
        }

        .upload-box:hover{
            background:#fffaf2;
            border-color:var(--gold);
        }

        .upload-icon{
            font-size:40px;
            color:var(--gold);
            margin-bottom:10px;
        }

        .btn-save{
            height:56px;
            border:none;
            border-radius:14px;
            background:linear-gradient(
                135deg,
                var(--gold),
                var(--gold-light)
            );
            color:var(--navy);
            font-weight:700;
            font-size:16px;
            transition:all .3s;
            box-shadow:
                0 6px 18px rgba(212,148,58,0.28);
        }

        .btn-save:hover{
            transform:translateY(-2px);
            box-shadow:
                0 10px 24px rgba(212,148,58,0.35);
        }

        .alert{
            border-radius:14px;
            border:none;
        }

        .section-divider{
            width:60px;
            height:4px;
            background:var(--gold);
            border-radius:20px;
            margin-bottom:25px;
        }

        @media(max-width:991px){

            .left-panel{
                display:none;
            }

            .right-panel{
                padding:35px 25px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="profile-card">

        <div class="row g-0">

            <!-- LEFT SIDE -->

            <div class="col-lg-5">

                <div class="left-panel h-100">

                    <div class="brand">
                        NFER<span>-EHVS</span>
                    </div>

                    <h1 class="hero-title">
                        Complete Your Employee Profile
                    </h1>

                    <p class="hero-text">

                        Secure your employment identity by completing your
                        professional profile for employment verification,
                        workforce tracking, and digital certification.

                    </p>

                    <div class="feature-list">

                        <div class="feature-item">

                            <div class="feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            Verified Employment Identity

                        </div>

                        <div class="feature-item">

                            <div class="feature-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>

                            Employment History Tracking

                        </div>

                        <div class="feature-item">

                            <div class="feature-icon">
                                <i class="bi bi-file-earmark-lock"></i>
                            </div>

                            Government-secured Records

                        </div>

                        <div class="feature-item">

                            <div class="feature-icon">
                                <i class="bi bi-patch-check"></i>
                            </div>

                            Trusted Verification Platform

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT SIDE -->

            <div class="col-lg-7">

                <div class="right-panel">

                    <div class="section-divider"></div>

                    <h2 class="form-title">
                        Employee Registration
                    </h2>

                    <p class="form-subtitle">
                        Fill in your personal and employment information.
                    </p>

                    <!-- ALERTS -->

                    @if (session('warning'))

                        <div class="alert alert-warning">

                            <i class="bi bi-exclamation-circle me-2"></i>

                            {{ session('warning') }}

                        </div>

                    @endif

                    <!-- ERRORS -->

                    @if ($errors->any())

                        <div class="alert alert-danger">

                            <strong>
                                Please fix the following errors:
                            </strong>

                            <ul class="mb-0 mt-2">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <!-- FORM -->

                    <form action="{{ route('employee.profile.store') }}"
                          method="POST"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="row">

                            <!-- NID -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    National ID (NID)
                                </label>

                                <input type="text"
                                       name="nid"
                                       class="form-control"
                                       placeholder="Enter NID"
                                       value="{{ old('nid') }}"
                                       required>

                            </div>

                            <!-- Gender -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Gender
                                </label>

                                <select name="gender"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="male"
                                        {{ old('gender') == 'male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="female"
                                        {{ old('gender') == 'female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                    <option value="other"
                                        {{ old('gender') == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                </select>

                            </div>

                            <!-- First Name -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    First Name
                                </label>

                                <input type="text"
                                       name="first_name"
                                       class="form-control"
                                       placeholder="First Name"
                                       value="{{ old('first_name') }}"
                                       required>

                            </div>

                            <!-- Last Name -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Last Name
                                </label>

                                <input type="text"
                                       name="last_name"
                                       class="form-control"
                                       placeholder="Last Name"
                                       value="{{ old('last_name') }}"
                                       required>

                            </div>

                            <!-- DOB -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Date of Birth
                                </label>

                                <input type="date"
                                       name="dob"
                                       class="form-control"
                                       value="{{ old('dob') }}"
                                       required>

                            </div>

                            <!-- Phone -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Phone Number
                                </label>

                                <input type="text"
                                       name="phone"
                                       class="form-control"
                                       placeholder="078XXXXXXX"
                                       value="{{ old('phone') }}"
                                       required>

                            </div>

                            <!-- Email -->

                            <div class="col-12 mb-4">

                                <label class="form-label">
                                    Email Address
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="example@email.com"
                                       value="{{ old('email') }}"
                                       required>

                            </div>

                            <!-- District -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    District
                                </label>

                                <input type="text"
                                       name="district"
                                       class="form-control"
                                       placeholder="District"
                                       value="{{ old('district') }}"
                                       required>

                            </div>

                            <!-- Sector -->

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    Sector
                                </label>

                                <input type="text"
                                       name="sector"
                                       class="form-control"
                                       placeholder="Sector"
                                       value="{{ old('sector') }}"
                                       required>

                            </div>

                            <!-- PHOTO -->

                            <div class="col-12 mb-4">

                                <label class="form-label">
                                    Profile Photo
                                </label>

                                <div class="upload-box">

                                    <div class="upload-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>

                                    <p class="mb-2 fw-semibold">
                                        Upload Profile Picture
                                    </p>

                                    <small class="text-muted">
                                        JPG, PNG up to 5MB
                                    </small>

                                    <input type="file"
                                           name="photo"
                                           class="form-control mt-3"
                                           accept="image/*">

                                </div>

                            </div>

                            <!-- BUTTON -->

                            <div class="col-12">

                                <button type="submit"
                                        class="btn btn-save w-100">

                                    <i class="bi bi-check-circle me-2"></i>

                                    Save Employee Profile

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>