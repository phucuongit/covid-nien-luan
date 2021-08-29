import { reactive } from "vue"

interface SideBarType {
  key: number
  link: string
  icon: string
  label: string
  children: SideBarType[]
}

export default function useConfigSideBar() {
  const sidebar = reactive<SideBarType[]>([
    {
      key: 1,
      link: "PopulateManager",
      icon: "bx:bx-search",
      label: "Thống kê",
      children: []
    },
    {
      key: 2,
      link: "PopulateManager",
      icon: "bx:bx-search",
      label: "Quản lý dân cư",
      children: []
    },
    {
      key: 3,
      link: "PopulateManager",
      icon: "bx:bx-search",
      label: "Thêm lịch sử tiêm",
      children: []
    },
    {
      key: 5,
      link: "Vaccine",
      icon: "bx:bx-search",
      label: "Vắc-xin",
      children: []
    },
    {
      key: 5,
      icon: "bx:bx-search",
      link: "",
      label: "Cài đặt",
      children: [
        {
          key: 1,
          link: "PopulateManager",
          icon: "bx:bx-search",
          label: "Tài khoản",
          children: []
        },
        {
          key: 2,
          link: "PopulateManager",
          icon: "bx:bx-search",
          label: "Người dùng",
          children: []
        }
      ]
    }
  ])
  return { sidebar }
}
