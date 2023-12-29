<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-container {
            width: 300px;
            padding: 30px;
            background: #1a1a1a;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .login-container h2 {
            text-align: center;
            color: #3498db;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #3498db;
            border-radius: 5px;
            font-size: 16px;
            color: #fff;
            background: #111;
            outline: none;
        }

        .login-container input:focus {
            border-color: #2980b9;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .login-container button:hover {
            background-color: #2980b9;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.5);
        }

        .login-container button:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #3498db, #2980b9);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: -1;
        }

        .login-container button:hover:before {
            opacity: 1;
        }

        .login-container a {
            display: block;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
        }

        .login-container a:hover {
            color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <form action="userlogin" method="post">
            @csrf
            <h2>Login</h2>
            <input type="text" placeholder="Enter UserId" name="userId">
            <input type="password" placeholder="Enter Password" name="password">
            <button type="submit">Login</button>
            <a href="#">Forgot Password?</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Check for success message
            if ('{{ session('success') }}') {
                toastr.options = {
                    "closeButton": true,
                    "timeOut": 3000
                };
                toastr.success('{{ session('success') }}');
            }

            // Check for error message
            if ('{{ session('error') }}') {
                toastr.options = {
                    "closeButton": true,
                    "timeOut": 3000
                };
                toastr.error('{{ session('error') }}');
            }
        });
    </script>
</body>

</html>
