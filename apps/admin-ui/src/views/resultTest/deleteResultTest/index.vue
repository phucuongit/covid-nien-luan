<script>
import { ref, defineComponent, inject, watch } from "vue"
import useDeleteResultTest from "./useDeleteResultTest.ts"
export default defineComponent({
  name: "DeleteUser",
  props: {
    isVisible: {
      type: Boolean
    },
    multipleSelection: {
      type: Object,
      require: true
    }
  },
  setup(props) {
    const closeDeleteResultTest = inject("closeDeleteResultTest")
    const getResultTestList = inject("getResultTestList")
    const currentPage = inject("currentPage")
    const listDelete = ref()
    const isShow = ref(false)
    watch(props, () => {
      listDelete.value = props.multipleSelection
      isShow.value = props.isVisible
    })

    const { deleteResultTest, isLoadingDelete } = useDeleteResultTest()

    const onSubmitDelete = async () => {
      const ids = listDelete.value.map((item) => item.id)
      for (let i in ids) {
        await deleteResultTest(ids[i])
        console.log(ids[i]) // Chưa có id để xóa
      }
      getResultTestList(currentPage.value)
      closeDeleteResultTest()
    }
    return {
      isShow,
      closeDeleteResultTest,
      listDelete,
      onSubmitDelete,
      isLoadingDelete
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
      <p>Bạn muốn xóa kết quả xét nghiệm của:</p>
      <ul v-for="user in listDelete" :key="user.id">
        <li>{{ user.user.fullname }}</li>
      </ul>
    </div>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          class="btn-11385e"
          @click="onSubmitDelete"
          :loading="isLoadingDelete"
          :disabled="isLoadingDelete"
          >Xóa</el-button
        >
        <el-button @click="closeDeleteResultTest">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>
