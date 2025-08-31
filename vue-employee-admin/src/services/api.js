import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  headers: {
    Accept: "application/json",
    Authorization: `Bearer ${localStorage.getItem("token") || ""}`,
  },
});

export function setToken(token) {
  localStorage.setItem("token", token);
  api.defaults.headers.Authorization = `Bearer ${token}`;
}

export default api;