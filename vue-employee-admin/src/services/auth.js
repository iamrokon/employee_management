import { setToken } from "./api";

export function login(token) {
  localStorage.setItem("token", token);
  setToken(token);
}

export function logout() {
  localStorage.removeItem("token");
  setToken(null);
}