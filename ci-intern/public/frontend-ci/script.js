let token = null; // JWT token storage

document.addEventListener('DOMContentLoaded', () => {
    // Login Button Event
    document.getElementById('loginBtn').addEventListener('click', () => {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email || !password) {
            alert('Enter email and password!');
            return;
        }

        // Login API call
        fetch('http://localhost:8080/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.token) {
                token = data.token;
                alert('Login Successful!');
                fetchTeachers(); // automatically fetch teachers after login
            } else {
                alert(data.messages?.error || 'Login failed');
            }
        })
        .catch(err => console.error('Login Error:', err));
    });
});

// Fetch teachers from protected route
function fetchTeachers() {
    fetch('http://localhost:8080/teacher', {
        method: 'GET',
        headers: { 'Authorization': 'Bearer ' + token }
    })
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('teacherList');
        list.innerHTML = '';
        if (!data || data.length === 0) {
            list.innerHTML = '<li>No teachers found</li>';
        } else {
            data.forEach(t => {
                const li = document.createElement('li');
                li.textContent = `${t.university_name} - ${t.gender} (${t.year_joined})`;
                list.appendChild(li);
            });
        }
    })
    .catch(err => console.error('Fetch Error:', err));
}
