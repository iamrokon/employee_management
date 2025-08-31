import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import EmployeeList from "../views/Employees/EmployeeList.vue";
import EmployeeForm from "../views/Employees/EmployeeForm.vue";
import EmployeeView from "../views/Employees/EmployeeView.vue";
import DepartmentList from "../views/Departments/DepartmentList.vue";
import Login from "../views/Login.vue";

const routes = [
  { path: "/login", name: "login", component: Login },               // <-- name added
  { path: "/", name: "dashboard", component: Dashboard },
  { path: "/employees", name: "employees", component: EmployeeList },
  { path: "/employees/new", name: "employee-new", component: EmployeeForm },
  { path: "/employees/:id", name: "employee-view", component: EmployeeView, props: true },
  { path: "/employees/:id/edit", name: "employee-edit", component: EmployeeForm, props: true },
  { path: "/departments", name: "departments", component: DepartmentList },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global guard: redirect to /login when there is no token
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token") !== "null";
  // token.value = token !== "null";

  // if not logged in and trying to visit any route other than login -> go to login
  if (!token && to.name !== "login") {
    return next({ name: "login" });
  }

  // if logged in and trying to visit login -> go to dashboard
  if (token && to.name === "login") {
    return next({ name: "dashboard" });
  }

  return next();
});

export default router;