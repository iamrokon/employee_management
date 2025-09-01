<template>
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">
      {{ isEdit ? "Edit Employee" : "Add New Employee" }}
    </h2>

    <form @submit.prevent="submitForm" class="space-y-4">
      <div>
        <label class="block mb-1 font-semibold">Name</label>
        <input v-model="form.name" type="text" class="border p-2 rounded w-full" required />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Email</label>
        <input v-model="form.email" type="email" class="border p-2 rounded w-full" required />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Department</label>
        <select v-model="form.department_id" class="border p-2 rounded w-full" required>
          <option value="">Select Department</option>
          <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{ dept.name }}</option>
        </select>
      </div>

      <div>
        <label class="block mb-1 font-semibold">Designation</label>
        <input v-model="form.detail.designation" type="text" class="border p-2 rounded w-full" required />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Salary</label>
        <input v-model.number="form.detail.salary" type="number" step="0.01" class="border p-2 rounded w-full" required />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Address</label>
        <input v-model="form.detail.address" type="text" class="border p-2 rounded w-full" />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Joining Date</label>
        <input v-model="form.detail.joined_date" type="date" class="border p-2 rounded w-full" required />
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
      >
        {{ isEdit ? "Update" : "Create" }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import api from "../../services/api";

const route = useRoute();
const router = useRouter();
const isEdit = ref(false);

const form = ref({
  name: "",
  email: "",
  department_id: "",
  detail: {
    designation: "",
    salary: 0,
    address: "",
    joined_date: ""
  }
});

const departments = ref([]);

async function fetchDepartments() {
  const res = await api.get("/departments");
  console.log("Departments API response:", res)
  departments.value = res.data;
}

async function fetchEmployee(id) {
  const res = await api.get(`/employees/${id}`);
  const emp = res.data.data;
  console.log("Employee API response:", emp)
  form.value.name = emp.name;
  form.value.email = emp.email;
  form.value.department_id = emp.department.id;
  form.value.detail = { ...emp.detail, joined_date: emp.detail.joined_date };
}

async function submitForm() {
  try {
    if (isEdit.value) {
      await api.put(`/employees/${route.params.id}`, form.value);
    } else {
      await api.post("/employees", form.value);
    }
    router.push("/employees");
  } catch (err) {
    console.error(err);
    alert("Something went wrong. Check console.");
  }
}

onMounted(async () => {
  await fetchDepartments();
  if (route.params.id) {
    isEdit.value = true;
    await fetchEmployee(route.params.id);
  }
});
</script>