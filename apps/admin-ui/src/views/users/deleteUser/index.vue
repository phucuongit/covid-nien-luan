<script>
import { ref, defineComponent, inject, watch } from "vue"
import useDeleteUser from "./useDeleteUser.ts"
export default defineComponent({
  name: "DeleteUser",
  props: {
    isVisible: {
      type: Boolean
    },
    selectUser: {
      type: Object,
      require: true
    }
  },
  setup(props) {
    const closeUserDelete = inject("closeUserDelete")
    const currentPage = inject("currentPage")
    const getListUsers = inject("getListUsers")
    const listDelete = ref()
    const isShow = ref(false)
    watch(props, () => {
      listDelete.value = props.selectUser
      isShow.value = props.isVisible
    })

    const { deleteUser } = useDeleteUser()

    const onSubmitDelete = async () => {
      const ids = listDelete.value.map((item) => item.id)
      for (let i in ids) {
        await deleteUser(ids[i])
        getListUsers(currentPage.value)
      }
      console.log("xong " + currentPage.value)
      closeUserDelete()
    }
    return {
      isShow,
      closeUserDelete,
      listDelete,
      onSubmitDelete
    }
  }
})
</script>

<template>
  <el-dialog
    title="Xóa người dùng"
    v-model="isShow"
    width="30%"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <div class="text-cent">
      <p>Bạn muốn xóa người dùng:</p>
      <ul v-for="user in listDelete" :key="user.id">
        <li>{{ user.fullname }}</li>
      </ul>
    </div>

    <template #footer>
      <span class="dialog-footer">
        <el-button type="primary" class="btn-11385e" @click="onSubmitDelete"
          >Xóa</el-button
        >
        <el-button @click="closeUserDelete">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>
