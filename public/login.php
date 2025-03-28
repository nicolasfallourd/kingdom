<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kingdom Management Game</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .form-group input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-btn {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: #2980b9;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: #3498db;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            text-align: center;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 16px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            window.supabaseClient = supabase.createClient(
                'https://iajhforizmdqzyzvfiqu.supabase.co',
                'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imlhamhmb3Jpem1kcXp5enZmaXF1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDMxNTY1NTcsImV4cCI6MjA1ODczMjU1N30.byoiCHewRowAHIq5toIGMuxrdgB5ojVc_dDzqdp7txI'
            );

            // Check if user is authenticated
            const { data: { user }, error } = await window.supabaseClient.auth.getUser();
            
            if (!user) {
                // If not authenticated, redirect to login
                window.location.href = 'login.php';
                return;
            }
            
            // Initialize game after authentication check
            initializeGame();
        });
    </script>
</head>
<body>
    <div class="login-container">
        <h1>Kingdom Management Game</h1>
        <div id="error-message" class="error-message"></div>
        <form class="login-form" id="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="#" id="register-link">Register here</a>
        </div>
    </div>

    <script>
        const supabase = supabase.createClient(
            'https://iajhforizmdqzyzvfiqu.supabase.co',
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imlhamhmb3Jpem1kcXp5enZmaXF1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDMxNTY1NTcsImV4cCI6MjA1ODczMjU1N30.byoiCHewRowAHIq5toIGMuxrdgB5ojVc_dDzqdp7txI'
        );

        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const { data, error } = await supabase.auth.signInWithPassword({
                    email: email,
                    password: password
                });

                if (error) throw error;

                // Redirect to game on successful login
                window.location.href = 'index.html';
            } catch (error) {
                document.getElementById('error-message').textContent = error.message;
            }
        });

        document.getElementById('register-link').addEventListener('click', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const { data, error } = await supabase.auth.signUp({
                    email: email,
                    password: password
                });

                if (error) throw error;

                document.getElementById('error-message').textContent = 'Registration successful! Please check your email.';
            } catch (error) {
                document.getElementById('error-message').textContent = error.message;
            }
        });
    </script>
</body>
</html> 