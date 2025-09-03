import React, { useEffect, useState } from 'react';
import axios from 'axios';

function Users() {
  const [users, setUsers] = useState([]);

  useEffect(() => {
    const fetchUsers = async () => {
      const token = localStorage.getItem('token');
      try {
        const res = await axios.get('http://localhost:8080/auth/profile', {
          headers: { 'Authorization': 'Bearer ' + token }
        });
        setUsers([res.data.profile]); // show current user
      } catch (err) {
        alert('Error fetching users');
      }
    };
    fetchUsers();
  }, []);

  return (
    <div style={{ padding: '20px' }}>
      <h2>Users List</h2>
      <table border="1" cellPadding="10">
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          {users.map(u => (
            <tr key={u.id}>
              <td>{u.id}</td>
              <td>{u.email}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default Users;
