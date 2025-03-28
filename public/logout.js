async function handleLogout() {
    try {
        const { error } = await window.supabaseClient.auth.signOut();
        if (error) throw error;
        window.location.href = 'login.php';
    } catch (error) {
        console.error('Error logging out:', error);
        alert('Error logging out');
    }
} 