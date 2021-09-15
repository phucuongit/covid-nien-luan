<script>
import { computed, defineComponent, inject } from "vue"
import useDeleteVaccineType from "./useDeleteVaccineType.ts"
export default defineComponent({
  name: "DeleteVaccineType",
  props: {
    isVisible: {
      type: Boolean
    },
    selectVaccineType: {
      type: Object,
      require: true
    }
  },
  setup(props) {
    const isShow = computed(() => {
      return props.isVisible
    })
    const closeVaccineTypeDelete = inject("closeVaccineTypeDelete")

    const listDelete = computed(() => {
      return props.selectVaccineType
    })
    const { deleteVaccineType } = useDeleteVaccineType()
    const getListVaccineType = inject("getListVaccineType")

    const onSubmitDelete = () => {
      const ids = listDelete.value.map((item) => item.id)
      for (let i in ids) {
        deleteVaccineType(ids[i], getListVaccineType)
      }
      closeVaccineTypeDelete()
    }
    return {
      isShow,
      closeVaccineTypeDelete,
      listDelete,
      onSubmitDelete
    }
  }
})
</script>

<template>
  <el-dialog
    title="Xóa loại vắc-xin"
    v-model="isShow"
    width="30%"
    destroy-on-close
    :close-on-click-modal="false"
    center
    :show-close="false"
  >
    <div class="text-cent">
      <p>Bạn có chắc chắn muốn xóa loại vắc-xin:</p>
      <ul v-for="vaccine in listDelete" :key="vaccine.id">
        <li>{{ vaccine.name }}</li>
      </ul>
    </div>

    <template #footer>
      <span class="dialog-footer">
        <el-button type="primary" class="btn-11385e" @click="onSubmitDelete"
          >Xóa</el-button
        >
        <el-button @click="closeVaccineTypeDelete">Thoát</el-button>
      </span>
    </template>
  </el-dialog>
</template>
