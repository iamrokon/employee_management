<template>
  <div class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold">Employee Management</h1>

    <div>
      <button
        v-if="!isLoggedIn"
        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition"
        @click="goLogin"
      >
        Login
      </button>

      <button
        v-else
        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
        @click="handleLogout"
      >
        Logout
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { logout } from "../services/auth";

const router = useRouter();
const isLoggedIn = ref(false);

// Check login status on mount
onMounted(() => {
  const token = localStorage.getItem("token");
  isLoggedIn.value = token !== "null";
});

// Logout function
function handleLogout() {
  logout();                  // remove token + clear axios header
  isLoggedIn.value = false;  // update button state
  router.replace("/login");  // redirect to login
}

// Login button navigation
function goLogin() {
  router.push("/login");
}
</script>

<style scoped>
button:hover {
  cursor: pointer;
}
</style>