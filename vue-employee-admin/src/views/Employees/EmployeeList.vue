<template>
  <div>
    <div class="flex justify-between mb-4">
      <h2 class="text-xl font-bold">Employees</h2>
      <router-link to="/employees/new" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add</router-link>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
      <input v-model="search" type="text" placeholder="Search..." class="border p-2 rounded w-full"/>

      <input v-model.number="salaryMin" type="number" placeholder="Min Salary" class="border p-2 rounded"/>
      <input v-model.number="salaryMax" type="number" placeholder="Max Salary" class="border p-2 rounded"/>

      <select v-model="departmentId" class="border p-2 rounded">
        <option value="">All Departments</option>
        <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{ dept.name }}</option>
      </select>
      <!-- Sort by joining date -->
      <select v-model="sort" class="border p-2 rounded">
        <option value="created_at" selected>Created Date</option>
        <option value="joined_date">Joining Date</option>
      </select>

      <select v-model="order" class="border p-2 rounded">
        <option value="desc" selected>Descending</option>
        <option value="asc">Ascending</option>
      </select>
    </div>

    <table class="w-full bg-white shadow rounded mb-4">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2">ID</th>
          <th class="p-2">Name</th>
          <th class="p-2">Email</th>
          <th class="p-2">Department</th>
          <th class="p-2">Salary</th>
          <th class="p-2">Joining Date</th>
          <th class="p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="emp in employees.data" :key="emp.id" class="border-b">
          <td class="p-2">{{ emp.id }}</td>
          <td class="p-2">{{ emp.name }}</td>
          <td class="p-2">{{ emp.email }}</td>
          <td class="p-2">{{ emp.department?.name }}</td>
          <td class="p-2">{{ emp.detail?.salary ?? "-" }}</td>
          <td class="p-2">{{ emp.detail?.joined_date ?? "-" }}</td>
          <td class="p-2">
            <router-link :to="`/employees/${emp.id}`" class="text-blue-500">View</router-link> |
            <router-link :to="`/employees/${emp.id}/edit`" class="text-green-500">Edit</router-link> |
            <button
              @click="deleteEmployee(emp.id)"
              class="text-red-500 hover:underline"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="flex justify-center space-x-2">
      <button
        class="px-3 py-1 border rounded"
        :disabled="!employees.prev_page_url"
        @click="fetchEmployees(employees.current_page - 1)"
      >
        Prev
      </button>

      <span>Page {{ employees.current_page }} of {{ employees.last_page }}</span>

      <button
        class="px-3 py-1 border rounded"
        :disabled="!employees.next_page_url"
        @click="fetchEmployees(employees.current_page + 1)"
      >
        Next
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import api from "../../services/api";

const employees = ref({ data: [], current_page: 1, last_page: 1 });
const departments = ref([]);

// Filters
const search = ref("");
const salaryMin = ref("");
const salaryMax = ref("");
const departmentId = ref("");
const sort = ref("created_at");
const order = ref("desc");

async function fetchEmployees(page = 1) {
  try {
    const res = await api.get("/employees", {
      params: {
        q: search.value || undefined,
        salary_min: salaryMin.value || undefined,
        salary_max: salaryMax.value || undefined,
        department_id: departmentId.value || undefined,
        sort: sort.value || "created_at",
        order: order.value || "desc",
        page, // pass page number
      },
    });

    // Laravel paginated response structure
    employees.value = {
      data: res.data.data,          // actual employee data
      current_page: res.data.meta.current_page,
      last_page: res.data.meta.last_page,
      prev_page_url: res.data.links.prev,
      next_page_url: res.data.links.next,
    };
  } catch (err) {
    console.error("Failed to fetch employees:", err);
  }
}

async function fetchDepartments() {
  const res = await api.get("/departments");
  departments.value = res.data;
}

async function deleteEmployee(id) {
  if (!confirm("Are you sure you want to delete this employee?")) return;

  try {
    await api.delete(`/employees/${id}`);
    // Refetch employees to update the list
    fetchEmployees(employees.value.current_page);
  } catch (error) {
    console.error("Failed to delete employee:", error);
    alert("Failed to delete employee. Try again.");
  }
}

onMounted(() => {
  fetchEmployees();
  fetchDepartments();
});

// Watch filters
watch([search, salaryMin, salaryMax, departmentId, sort, order], () => fetchEmployees(1));
</script>