<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kingdom Management Game</title>
    <link rel="stylesheet" href="/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <style>
        body {
            background-color: #2c3e50;
            color: #bbb;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #34495e;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            border: 1px solid #3498db;
        }

        h1 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
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
            color: #fff;
        }

        .form-group input {
            padding: 12px;
            border: 1px solid #3498db;
            background-color: #2c3e50;
            color: #fff;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-btn {
            background-color: #3498db;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        .login-btn:hover {
            background-color: #2980b9;
        }
        
        .register-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        .register-btn:hover {
            background-color: #45a049;
        }
        
        .anonymous-btn {
            background-color: #f39c12;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            font-weight: bold;
            margin-top: 10px;
        }

        .anonymous-btn:hover {
            background-color: #e67e22;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            color: #bbb;
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
            font-weight: bold;
            padding: 10px;
            background-color: rgba(231, 76, 60, 0.1);
            border-radius: 4px;
            border-left: 4px solid #e74c3c;
        }
        
        .success-message {
            color: #4CAF50;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            padding: 10px;
            background-color: rgba(76, 175, 80, 0.1);
            border-radius: 4px;
            border-left: 4px solid #4CAF50;
        }

        .form-toggle {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .form-toggle button {
            background: none;
            border: none;
            color: #bbb;
            cursor: pointer;
            padding: 5px 15px;
            font-size: 16px;
            border-bottom: 2px solid transparent;
        }

        .form-toggle button.active {
            color: #fff;
            border-bottom: 2px solid #3498db;
        }
        
        .loading {
            text-align: center;
            margin: 20px 0;
            color: #fff;
        }
        
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Kingdom Management Game</h1>
        
        <div class="form-toggle">
            <button id="login-toggle" class="active">Login</button>
            <button id="register-toggle">Register</button>
        </div>
        
        <div id="message-container"></div>
        <div id="loading-container" style="display: none;">
            <div class="spinner"></div>
            <p class="loading">Processing...</p>
        </div>
        
        <form class="login-form" id="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn" id="login-button">Login</button>
        </form>
        
        <form class="login-form" id="register-form" style="display: none;">
            <div class="form-group">
                <label for="register-email">Email</label>
                <input type="email" id="register-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="register-password">Password</label>
                <input type="password" id="register-password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label for="register-username">Username</label>
                <input type="text" id="register-username" name="username" required>
            </div>
            <button type="submit" class="register-btn" id="register-button">Register</button>
        </form>
        
        <button id="anonymous-login" class="anonymous-btn">Play as Guest</button>
    </div>

    <script>
        // Initialize Supabase client
        const supabase = supabase.createClient(
            'https://iajhforizmdqzyzvfiqu.supabase.co',
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imlhamhmb3Jpem1kcXp5enZmaXF1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDMxNTY1NTcsImV4cCI6MjA1ODczMjU1N30.byoiCHewRowAHIq5toIGMuxrdgB5ojVc_dDzqdp7txI'
        );
        
        // Check if user is already logged in
        document.addEventListener('DOMContentLoaded', async function() {
            const { data: { user }, error } = await supabase.auth.getUser();
            
            if (user) {
                // User is already logged in, redirect to game
                window.location.href = '/index.html';
            }
        });
        
        // Form toggle functionality
        const loginToggle = document.getElementById('login-toggle');
        const registerToggle = document.getElementById('register-toggle');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        loginToggle.addEventListener('click', () => {
            loginToggle.classList.add('active');
            registerToggle.classList.remove('active');
            loginForm.style.display = 'flex';
            registerForm.style.display = 'none';
            clearMessages();
        });
        
        registerToggle.addEventListener('click', () => {
            registerToggle.classList.add('active');
            loginToggle.classList.remove('active');
            registerForm.style.display = 'flex';
            loginForm.style.display = 'none';
            clearMessages();
        });
        
        // Helper functions
        function showLoading(show) {
            const loadingContainer = document.getElementById('loading-container');
            loadingContainer.style.display = show ? 'block' : 'none';
        }
        
        function showMessage(message, isError = true) {
            const messageContainer = document.getElementById('message-container');
            messageContainer.innerHTML = `<div class="${isError ? 'error-message' : 'success-message'}">${message}</div>`;
        }
        
        function clearMessages() {
            const messageContainer = document.getElementById('message-container');
            messageContainer.innerHTML = '';
        }
        
        // Login form submission
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            clearMessages();
            showLoading(true);
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const { data, error } = await supabase.auth.signInWithPassword({
                    email: email,
                    password: password
                });

                if (error) throw error;

                // Redirect to game on successful login
                window.location.href = '/index.html';
            } catch (error) {
                showLoading(false);
                showMessage(error.message);
            }
        });

        // Register form submission
        document.getElementById('register-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            clearMessages();
            showLoading(true);
            
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const username = document.getElementById('register-username').value;

            try {
                // First register the user with Supabase Auth
                const { data, error } = await supabase.auth.signUp({
                    email: email,
                    password: password,
                    options: {
                        data: {
                            username: username
                        }
                    }
                });

                if (error) throw error;
                
                showLoading(false);
                showMessage('Registration successful! You can now log in.', false);
                
                // Switch to login form
                loginToggle.click();
            } catch (error) {
                showLoading(false);
                showMessage(error.message);
            }
        });
        
        // Anonymous login
        document.getElementById('anonymous-login').addEventListener('click', async () => {
            clearMessages();
            showLoading(true);
            
            try {
                // Generate a more reliable random email
                const randomId = Math.random().toString(36).substring(2, 10) + Math.random().toString(36).substring(2, 10);
                const anonymousEmail = `user_${randomId}@kingdom-game.com`;
                
                // Generate a secure random password
                const randomPassword = Math.random().toString(36).substring(2, 10) + 
                                      Math.random().toString(36).substring(2, 10) + 
                                      Math.random().toString(36).substring(2, 10);
                
                const { data, error } = await supabase.auth.signUp({
                    email: anonymousEmail,
                    password: randomPassword,
                    options: {
                        data: {
                            username: `Guest_${randomId.substring(0, 6)}`
                        }
                    }
                });
                
                if (error) throw error;
                
                // Automatically sign in after registration
                const { error: signInError } = await supabase.auth.signInWithPassword({
                    email: anonymousEmail,
                    password: randomPassword
                });
                
                if (signInError) throw signInError;
                
                // Redirect to game
                window.location.href = '/index.html';
            } catch (error) {
                showLoading(false);
                showMessage(error.message);
            }
        });
    </script>
</body>
</html>