<template>
  <aside class="w-64 bg-gray-800 text-white min-h-screen">
    <div class="p-4 text-lg font-bold">Admin Panel</div>
    <nav class="space-y-2">
      <router-link
        to="/"
        class="block px-4 py-2 hover:bg-gray-700 rounded"
      >Dashboard</router-link>
      <router-link
        to="/employees"
        class="block px-4 py-2 hover:bg-gray-700 rounded"
      >Employees</router-link>
      <router-link
        to="/departments"
        class="block px-4 py-2 hover:bg-gray-700 rounded"
      >Departments</router-link>
    </nav>
  </aside>
</template>

<script setup>
// No JS needed here yet
</script>

<style scoped>
a:hover {
  cursor: pointer;
}
</style>