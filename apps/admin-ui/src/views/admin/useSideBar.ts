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
      icon: "carbon:dashboard",
      label: "Thống kê",
      children: []
    },
    {
      key: 2,
      link: "PopulateManager",
      icon: "carbon:id-management",
      label: "Quản lý dân cư",
      children: []
    },
    {
      key: 3,
      link: "PopulateManager",
      icon: "lucide:history",
      label: "Thêm lịch sử tiêm",
      children: []
    },
    {
      key: 5,
      link: "Vaccine",
      icon: "ic:twotone-vaccines",
      label: "Vắc-xin",
      children: []
    },
    {
      key: 5,
      icon: "ant-design:setting-twotone",
      link: "",
      label: "Cài đặt",
      children: [
        {
          key: 1,
          link: "PopulateManager",
          icon: "carbon:user-avatar-filled-alt",
          label: "Tài khoản",
          children: []
        },
        {
          key: 2,
          link: "PopulateManager",
          icon: "fa-solid:users",
          label: "Người dùng",
          children: []
        }
      ]
    }
  ])
  return { sidebar }
}
