<template>
  <div class="admin" v-if="token">
    <h1>This is admin page</h1>
  </div>
</template>

<script>
import { useStore } from "vuex"
import { defineComponent } from "vue"
import router from "@/router"

export default defineComponent({
  setup() {
    const token = localStorage.getItem("token")
    const store = useStore()
    if (token) {
      store.dispatch("setToken", token)
    } else {
      router.push({ name: "Login" })
      store.dispatch("setToken", "")
    }

    return { token }
  }
})
</script>
