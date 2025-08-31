<template>
  <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>

    <form @submit.prevent="login" class="space-y-4">
      <div>
        <label class="block mb-1 font-semibold">Email</label>
        <input v-model="form.email" type="email" class="border p-2 rounded w-full" required />
      </div>

      <div>
        <label class="block mb-1 font-semibold">Password</label>
        <input v-model="form.password" type="password" class="border p-2 rounded w-full" required />
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 transition"
      >
        Login
      </button>
    </form>

    <p v-if="error" class="mt-4 text-red-500">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api, { setToken } from "../services/api";

const router = useRouter();

const form = ref({
  email: "",
  password: "",
});

const error = ref("");

async function login() {
  error.value = "";
  try {
    const res = await api.post("/login", form.value); // your Laravel login API
    const token = res.data.token; // assuming Laravel returns { token: "..." }
    
    // Save token in localStorage and Axios
    setToken(token);

    // Redirect to dashboard
    router.push("/dashboard");
  } catch (err) {
    console.error(err);
    error.value =
      err.response?.data?.message || "Login failed. Check credentials.";
  }
}
</script>