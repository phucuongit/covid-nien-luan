import API from "@/services"
import { ref } from "vue"

export type userType = {
  id: number
  identify_card: string
  social_insurance: string
  fullname: string
  birthday: null
  gender: number
  username: string
  address: string
  address_full: []
  phone: string
  village_id: number
  role: {
    name: string
  }
  created_at: null
  updated_at: null
  images: []
}

function useUsers() {
  const data = ref()
  const loadingListUser = ref(false)
  const loadingSearch = ref(false)
  const statusSearchUser = ref(false)
  const getListUsers = async (page: number) => {
    try {
      statusSearchUser.value = false
      loadingListUser.value = true
      const response = await API.get("user?page=" + page)
      if (response.data.success) {
        data.value = response.data.data
      }
    } catch (e) {
      console.log(e)
      loadingListUser.value = false
    } finally {
      loadingListUser.value = false
    }
  }

  const getListUsersSearch = async (text: string, page: number) => {
    try {
      statusSearchUser.value = true
      loadingListUser.value = true
      loadingSearch.value = true
      const responseSearch = await API.get(
        "user?search=" + text + "&page=" + page
      )
      if (responseSearch.data.success) {
        data.value = responseSearch.data.data
      }
    } catch (e) {
      console.log(e)
      loadingListUser.value = false
      loadingSearch.value = false
    } finally {
      loadingListUser.value = false
      loadingSearch.value = false
    }
  }

  return {
    data,
    loadingListUser,
    getListUsers,
    getListUsersSearch,
    loadingSearch,
    statusSearchUser
  }
}

export default useUsers
