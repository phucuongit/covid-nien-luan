import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router"
import Home from "../views/Home.vue"
import Admin from "../views/admin/index.vue"
import Login from "../views/login/index.vue"
import ResultTest from "../views/resultTest/index.vue"
import Vaccine from "../views/vaccineType/index.vue"
import Users from "../views/users/index.vue"
import Vaccination from "../views/vaccination/index.vue"
import NotFound from "../views/notFound/index.vue"
import Dashboard from "../views/Dashboard/index.vue"
import Account from "../views/account/index.vue"

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    name: "Home",
    component: Home
  },
  {
    path: "/admin",
    name: "Admin",
    component: Admin,
    children: [
      {
        path: "/admin",
        name: "Dashboard",
        component: Dashboard
      },
      {
        path: "result-test",
        name: "ResultTest",
        component: ResultTest
      },
      {
        path: "vaccine",
        name: "Vaccine",
        component: Vaccine
      },
      {
        path: "users",
        name: "Users",
        component: Users
      },
      {
        path: "vaccination",
        name: "Vaccination",
        component: Vaccination
      },
      {
        path: "account",
        name: "Account",
        component: Account
      }
    ],
    meta: {
      requireAuth: true
    }
  },
  {
    path: "/admin/login",
    name: "Login",
    component: Login
  },
  {
    path: "/:pathMatch(.*)",
    name: "NotFound",
    component: NotFound
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from) => {
  const token = localStorage.getItem("token")
  if (to.meta.requireAuth && !token) {
    return { name: "Login" }
  }
})

export default router
