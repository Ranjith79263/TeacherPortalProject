A Fullstack Teacher Portal Application that allows teachers to manage their profile.

🌐 Fullstack implementation with frontend + backend + database

🛠️ Tech Stack

Frontend:

React.js

Axios (API calls)

css/javascript

Backend:

Node.js

Express.js

JWT Authentication

RESTful APIs

Database:

MySQL / PostgreSQL
TeacherPortalProject/
│── frontend/        # React frontend
│   ├── public/
│   ├── src/
│   └── package.json
│
│── backend/         # Node.js backend
│   ├── controllers/
│   ├── models/
│   ├── routes/
│   ├── config/
│   ├── server.js
│   └── package.json
│
│── database/        # SQL scripts (auth_user, teachers tables)
│   └── db_intern.sql
│
└── README.md        # Project documentation
Installation and setup
git clone https://github.com/your-username/TeacherPortalProject.git
cd TeacherPortalProject
Backend setup
cd backend
npm install
.env  file
PORT=5000
DB_HOST=localhost
DB_USER=root
DB_PASS=password
DB_NAME=db_intern
JWT_SECRET=your_secret_key
start backend
npm start
start front end
cd frontend
npm install
npm start
🗄️ Database Schema
Table: auth_user
Column	Type	Description
id	INT (PK)	Unique User ID
username	VARCHAR	Login username
email	VARCHAR	User email
password	VARCHAR	Encrypted password
Table: teachers
Column	Type	Description
id	INT (PK)	Teacher ID
user_id	INT (FK)	Linked to auth_user
name	VARCHAR	Teacher full name
subject	VARCHAR	Subject specialization
contact	VARCHAR	Contact number



