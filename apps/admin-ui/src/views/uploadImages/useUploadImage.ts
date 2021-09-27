import API from "@/services"
import { ElMessage } from "element-plus"
import { ref } from "vue"

type imageType = {
  images: null
  imageable_id: number
  imageable_type: string
  type: null
}

function useUploadImage() {
  const isLoadingRemoveImage = ref(false)
  const isLoadingUpdateImage = ref(false)
  const percent = ref(0)
  const uploadImage = async (params: imageType) => {
    try {
      const response = await API.post("file/image", params, {
        onUploadProgress: (uploadEvent) => {
          console.log(
            "Upload progress: " +
              Math.round((uploadEvent.loaded / uploadEvent.total) * 100)
          )
          percent.value = Math.round(
            (uploadEvent.loaded / uploadEvent.total) * 100
          )
        }
      })

      if (response.data.success) {
        ElMessage.success({
          message: "Tải ảnh lên thành công",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Tải ảnh lên không thành công",
          type: "error"
        })
      }
    } catch (e) {
      console.log(e)
      ElMessage.error({
        message: "Tải ảnh lên không thành công",
        type: "error"
      })
    }
  }

  const deleteImage = async (id: number) => {
    try {
      isLoadingRemoveImage.value = true
      const response = await API.delete("file/image/" + id)
      if (response.data.success) {
        ElMessage.success({
          message: "Xóa ảnh thành công",
          type: "success"
        })
      } else {
        ElMessage.error({
          message: "Xóa ảnh không thành công",
          type: "error"
        })
      }
    } catch (e) {
      isLoadingRemoveImage.value = false
      console.log(e)
      ElMessage.error({
        message: "Xóa ảnh không thành công",
        type: "error"
      })
    } finally {
      isLoadingRemoveImage.value = false
    }
  }

  const updateImage = async (id: number, params: imageType) => {
    try {
      isLoadingUpdateImage.value = true
      const responseDelete = await API.delete("file/image/" + id)
      if (responseDelete.data.success) {
        const response = await API.post("file/image", params)
        if (response.data.success) {
          ElMessage.success({
            message: "Cập nhật ảnh thành công",
            type: "success"
          })
        } else {
          ElMessage.error({
            message: "Cập nhật ảnh không thành công",
            type: "error"
          })
        }
      } else {
        ElMessage.error({
          message: "Cập nhật ảnh không thành công",
          type: "error"
        })
      }
    } catch (e) {
      isLoadingUpdateImage.value = false
      console.log(e)
      ElMessage.error({
        message: "Cập nhật ảnh không thành công",
        type: "error"
      })
    } finally {
      isLoadingUpdateImage.value = false
    }
  }

  return {
    uploadImage,
    deleteImage,
    isLoadingRemoveImage,
    isLoadingUpdateImage,
    updateImage,
    percent
  }
}

export default useUploadImage
