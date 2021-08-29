import axios from "axios"
const API = axios.create({
  baseURL: "https://api-nienluan.sharenows.com/api/v1/auth/"
})

API.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token")
    if (token) {
      config.headers["Authorization"] = token
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

export default API
