import { createApp } from "vue"
import App from "./App.vue"
import "./registerServiceWorker"
import router from "@/router"
import store from "./store"
import ElementPlus from "element-plus"
import "element-plus/lib/theme-chalk/index.css"
import "element-plus/lib/theme-chalk/display.css"
import "./assets/css/style.css?v=<?=time()?>"
import "./assets/css/responsive.css?v=<?=time()?>"

const app = createApp(App)
app.use(store)
app.use(router)
app.use(ElementPlus)
app.mount("#app")
