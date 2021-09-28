<script>
import { computed, defineComponent, ref, inject, watch } from "vue"
import useDeleteVaccination from "./useDeleteVaccination.ts"
export default defineComponent({
  name: "DeleteVaccination",
  props: {
    isVisible: {
      type: Boolean
    },
    selectVaccination: {
      type: Object,
      require: true
    }
  },
  setup(props) {
    const visible = ref(false)
    const listDelete = ref()

    const { deleteVaccination, isLoadingDeleteVaccination } =
      useDeleteVaccination()
    const getVaccinationList = inject("getVaccinationList")
    const currentPage = inject("currentPage")
    const closeVaccinationDelete = inject("handleVisisbleDelete")

    watch(props, () => {
      visible.value = props.isVisible
      listDelete.value = props.selectVaccination
    })

    const onSubmitDelete = async () => {
      const ids = listDelete.value.map((item) => item.id)
      for (let i in ids) {
        await deleteVaccination(ids[i])
      }
      getVaccinationList(currentPage.value)
      closeVaccinationDelete()
    }
    return {
      visible,
      closeVaccinationDelete,
      listDelete,
      onSubmitDelete,
      isLoadingDeleteVaccination
    }
  }
})
</script>

<template>
  <el-dialog
    title="Xóa lịch sử tiêm chủng"
    v-model="visible"
    width="30%"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <div class="text-cent">
      <p>Bạn có chắc chắn muốn xóa loại lịch sử tiêm của:</p>
      <ul v-for="vaccination in listDelete" :key="vaccination.id">
        <li>{{ vaccination.user.fullname }}</li>
      </ul>
    </div>

    <template #footer>
      <span class="dialog-footer">
        <el-button
          type="primary"
          class="btn-11385e"
          @click="onSubmitDelete"
          :disabled="isLoadingDeleteVaccination"
          :loading="isLoadingDeleteVaccination"
          >Xóa</el-button
        >
        <el-button @click="closeVaccinationDelete">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>
