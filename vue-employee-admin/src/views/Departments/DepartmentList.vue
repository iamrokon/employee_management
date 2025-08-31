<template>
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Departments</h2>

    <table class="w-full bg-white shadow rounded">
      <thead>
        <tr class="bg-gray-200 text-left">
          <th class="p-2">ID</th>
          <th class="p-2">Name</th>
          <th class="p-2">Description</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="dept in departments" :key="dept.id" class="border-b">
          <td class="p-2">{{ dept.id }}</td>
          <td class="p-2">{{ dept.name }}</td>
          <td class="p-2">{{ dept.description }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "../../services/api";

const departments = ref([]);

async function fetchDepartments() {
  try {
    const res = await api.get("/departments");
    console.log("Departments API response:", res)
    departments.value = res.data; // assuming API returns { data: [...] }
  } catch (err) {
    console.error(err);
    alert("Failed to fetch departments.");
  }
}

onMounted(fetchDepartments);
</script>

<style scoped>
table {
  border-collapse: collapse;
}
th, td {
  border: 1px solid #e5e7eb;
}
</style>