<template>
  <div class="text-center">
    <h1>404</h1>
    <h3>Không tìm thấy trang</h3>
    <router-link :to="{ name: 'Home' }">
      <el-button type="primary"> Quay lại trang chủ </el-button>
    </router-link>
  </div>
</template>

<script>
import { defineComponent } from "vue"

export default defineComponent({
  name: "NotFound"
})
</script>
