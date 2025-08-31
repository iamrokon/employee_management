Employee Management System
A simple Employee Management System API built with Laravel 12 for the backend and Vue 3 + TailwindCSS for the frontend.
The system supports employee CRUD operations, searching, filtering, sorting, pagination, and department association.
________________________________________
Features
Backend (Laravel)
•	Employee CRUD (Create, Read, Update, Delete) with soft delete
•	Employee details: designation, salary, joining date
•	Search employees by name or email
•	Filter employees by:
o	Department
o	Salary range
•	Sort employees by:
o	Created date (created_at)
o	Joining date (joined_date)
•	Pagination
•	Resource-based API responses
•	Transaction-safe employee creation and updates
•	Indexing for optimized queries
Frontend (Vue 3 + TailwindCSS)
•	List employees with pagination
•	Filters:
o	Search
o	Salary range
o	Department
•	Sort by joining date (asc/desc)
•	Employee detail view and edit
•	Responsive layout
________________________________________
Installation
Backend
1.	Clone the repository
 	git clone <repo_url>
cd employee-management
2.	Install PHP dependencies
 	composer install
3.	Copy .env and configure database
 	cp .env.example .env
 	Update .env with your DB credentials.
4.	Generate app key
 	php artisan key:generate
5.	Run migrations and seeders
 	php artisan migrate --seed
6.	Run the development server
 	php artisan serve
________________________________________
Frontend
1.	Navigate to the frontend directory
 	cd frontend
2.	Install npm dependencies
 	npm install
3.	Run the development server
 	npm run dev
4.	Open your browser at http://localhost:5173 (or as shown in terminal)
5. Hit the login button and fill the form with followings:
  email: test@example.com
  password: password
________________________________________
API Endpoints
Employees
Method	Endpoint	Description
GET	/api/employees	List employees with filters, sorting, and pagination
POST	/api/employees	Create a new employee
GET	/api/employees/{id}	Show employee details
PUT/PATCH	/api/employees/{id}	Update employee details
DELETE	/api/employees/{id}	Soft delete employee
Departments
Method	Endpoint	Description
GET	/api/departments	List all departments
________________________________________
Filters & Sorting
•	Search: q query parameter (search by name or email)
•	Department: department_id
•	Salary Range: salary_min, salary_max
•	Sorting:
o	sort: created_at or joined_date
o	order: asc or desc
•	Pagination: per_page and page parameters
________________________________________
Example API Responses
List Employees
{
  "data": [
    {
      "id": "fc81ee09-082f-45fa-92ec-6afdde4963f2",
      "name": "Md. Rokonuzzaman Pk",
      "email": "rokonuzzamancse@gmail.com",
      "department": {
        "id": 7,
        "name": "Cole, Collins and Beer"
      },
      "detail": {
        "designation": "Software engineer",
        "salary": 70000,
        "joined_date": "2025-09-11",
        "address": null
      },
      "created_at": "2025-08-31T20:18:55.000000Z",
      "updated_at": null
    },
  ]
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "path": "...",
    "per_page": 10,
    "to": 10,
    "total": 50
  }
}
Create Employee
{
  "name": "Faruk",
  "email": "faruk@gmail.com",
  "department_id": 2,
  "detail": {
    "designation": "Misti",
    "salary": 40000.00,
    "address": "Merul Badda, Dhaka",
    "joined_date": "2023-07-15"
  }
}
________________________________________
Testing
•	Run PHPUnit tests:
php artisan test
•	Tests cover:
o	Employee listing with filters and pagination
o	Employee creation, update, and soft deletion
o	Search and sorting
________________________________________
Database Indexes
•	Indexed columns for performance:
o	employees.name
o	employees.email
o	employees.department_id
o	Composite index for deleted_at and created_at
o	Foreign key indexes for department relation
________________________________________
Frontend Filters
•	Search bar for name/email
•	Salary min/max inputs
•	Department dropdown
•	Sort by joining date
•	Pagination controls
________________________________________
Contributing
1.	Fork the repo
2.	Create a feature branch (git checkout -b feature/my-feature)
3.	Commit your changes (git commit -am 'Add feature')
4.	Push to the branch (git push origin feature/my-feature)
5.	Open a Pull Request
________________________________________
License
This project is licensed under the MIT License.
