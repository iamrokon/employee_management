import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import "./index.css";

// Import API service
import { setToken } from "./services/api";

const savedToken = localStorage.getItem("token");
if (savedToken) {
  setToken(savedToken);
}

createApp(App).use(router).mount("#app");