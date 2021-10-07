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
    meta: {
      title: "Thống kê"
    },
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
        meta: {
          title: "Quản trị"
        },
        component: Dashboard
      },
      {
        path: "result-test",
        name: "ResultTest",
        meta: {
          title: "Danh sách kết quả xét nghiệm"
        },
        component: ResultTest
      },
      {
        path: "vaccine",
        name: "Vaccine",
        meta: {
          title: "Danh sách tiêm chủng vắc-xin"
        },
        component: Vaccine
      },
      {
        path: "users",
        name: "Users",
        meta: {
          title: "Danh sách người dùng"
        },
        component: Users
      },
      {
        path: "vaccination",
        name: "Vaccination",
        meta: {
          title: "Danh sách tiêm chủng vắc-xin"
        },
        component: Vaccination
      },
      {
        path: "account",
        name: "Account",
        meta: {
          title: "Tài khoản"
        },
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
    meta: {
      title: "Đăng nhập"
    },
    component: Login
  },
  {
    path: "/:pathMatch(.*)",
    name: "NotFound",
    meta: {
      title: "404"
    },
    component: NotFound
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from) => {
  // @ts-ignore
  document.title = to.meta.title || "Your Website Title"

  const token = localStorage.getItem("token")
  if (to.meta.requireAuth && !token) {
    return { name: "Login" }
  }
})

export default router
