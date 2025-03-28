<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out - Kingdom Management Game</title>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <style>
        body {
            background-color: #2c3e50;
            color: #bbb;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .logout-container {
            text-align: center;
            max-width: 400px;
            padding: 20px;
            background-color: #34495e;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            border: 1px solid #3498db;
        }
        
        h1 {
            color: #fff;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>Logging Out</h1>
        <div class="spinner"></div>
        <p>Please wait while we log you out...</p>
    </div>

    <script>
        // Initialize Supabase client
        const supabase = supabase.createClient(
            'https://iajhforizmdqzyzvfiqu.supabase.co',
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imlhamhmb3Jpem1kcXp5enZmaXF1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDMxNTY1NTcsImV4cCI6MjA1ODczMjU1N30.byoiCHewRowAHIq5toIGMuxrdgB5ojVc_dDzqdp7txI'
        );
        
        // Perform logout
        async function performLogout() {
            try {
                const { error } = await supabase.auth.signOut();
                if (error) throw error;
                
                // Redirect to login page after successful logout
                window.location.href = '/login.php';
            } catch (error) {
                console.error('Error logging out:', error);
                // Still redirect to login page even if there's an error
                window.location.href = '/login.php';
            }
        }
        
        // Execute logout when page loads
        document.addEventListener('DOMContentLoaded', performLogout);
    </script>
</body>
</html>