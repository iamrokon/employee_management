<template>
  <div class="flex min-h-screen bg-gray-100">
    <Sidebar />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import Navbar from "../components/Navbar.vue";
import Sidebar from "../components/Sidebar.vue";
</script>