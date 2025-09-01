<template>
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Employee Details</h2>

    <div v-if="employee">
      <p><strong>Name:</strong> {{ employee.name }}</p>
      <p><strong>Email:</strong> {{ employee.email }}</p>
      <p><strong>Department:</strong> {{ employee.department?.name }}</p>
      <p><strong>Designation:</strong> {{ employee.detail?.designation }}</p>
      <p><strong>Salary:</strong> {{ employee.detail?.salary }}</p>
      <p><strong>Address:</strong> {{ employee.detail?.address }}</p>
      <p><strong>Joining Date:</strong> {{ employee.detail?.joined_date }}</p>

      <div class="mt-4">
        <router-link
          :to="`/employees/${employee.id}/edit`"
          class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
        >Edit</router-link>
      </div>
    </div>

    <div v-else>
      Loading employee...
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import api from "../../services/api";

const route = useRoute();
const employee = ref(null);

async function fetchEmployee() {
  try {
    const res = await api.get(`/employees/${route.params.id}`);
    employee.value = res.data.data;
  } catch (err) {
    console.error(err);
    alert("Failed to fetch employee.");
  }
}

onMounted(fetchEmployee);
</script>