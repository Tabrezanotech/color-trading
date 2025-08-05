<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            border-radius: 20px;
            background: linear-gradient(135deg, #ffffff, #d0f0f9);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .account-heading {
            color: #0d6efd;
            font-weight: 700;
        }
        .wallet-balance {
            color: #198754;
            font-weight: bold;
            font-size: 1.4rem;
        }
        .user-id {
            color: #6c757d;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="text-center account-heading mb-5">Account</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 text-center">
                <div class="card-body">
                    <h4 class="wallet-balance mb-3">
                        Wallet Balance: ₹{{ number_format($user->wallet, 2) }}
                    </h4>
                    <h5 class="user-id">
                        User ID: #{{ $user->id }}
                    </h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
