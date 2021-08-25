<template>
  <div class="admin" v-if="token">
    <h1>This is admin page</h1>
  </div>
</template>

<script>
import { defineComponent } from "vue"
import router from "@/router"

export default defineComponent({
  setup() {
    const token = localStorage.getItem("token")
    if (!token) {
      console.log('fail')
      router.push({ name: "Login" })
    }

    return { token }
  }
})
</script>
