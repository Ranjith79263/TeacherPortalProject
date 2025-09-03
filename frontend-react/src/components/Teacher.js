import React, { useEffect, useState } from 'react';
import axios from 'axios';
import './Teacher.css'; // <-- import CSS

function Teachers() {
  const [teachers, setTeachers] = useState([]);

  useEffect(() => {
    const fetchTeachers = async () => {
      const token = localStorage.getItem('token');
      try {
        const res = await axios.get('http://localhost:8080/teacher', {
          headers: { Authorization: 'Bearer ' + token },
        });
        setTeachers(res.data);
      } catch (err) {
        alert('Error fetching teachers');
      }
    };
    fetchTeachers();
  }, []);

  return (
    <div className="teachers-container">
      <div className="teachers-card">
        <h2>Teachers List</h2>
        <table className="teachers-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>University</th>
              <th>Gender</th>
              <th>Year Joined</th>
            </tr>
          </thead>
          <tbody>
            {teachers.map((t) => (
              <tr key={t.id}>
                <td>{t.id}</td>
                <td>{t.university_name}</td>
                <td>{t.gender}</td>
                <td>{t.year_joined}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default Teachers;
