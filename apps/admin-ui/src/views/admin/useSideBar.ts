import { reactive } from "vue"

export default function useConfigSideBar() {
  const sidebar = reactive([
    {
      key: 1,
      link: "AdminHome",
      icon: "el-icon-s-home",
      label: "Trang chủ",
      children: []
    },
    {
      key: 2,
      link: "PopulateManager",
      icon: "el-icon-s-management",
      label: "Quản lý dân cư",
      children: []
    },
    {
      key: 3,
      link: "PopulateManager",
      icon: "el-icon-document-add",
      label: "Thêm lịch sử tiêm",
      children: []
    },
    {
      key: 4,
      link: "PopulateManager",
      icon: "el-icon-tickets",
      label: "Thống kê",
      children: []
    },
    {
      key: 5,
      link: "Vaccine",
      icon: "el-icon-s-order",
      label: "Vắc-xin",
      children: []
    },
    {
      key: 6,
      icon: "el-icon-s-tools",
      label: "Cài đặt",
      children: [
        {
          key: 1,
          link: "PopulateManager",
          icon: "el-icon-s-custom",
          label: "Tài khoản"
        },
        {
          key: 2,
          link: "PopulateManager",
          icon: "el-icon-s-custom",
          label: "Người dùng"
        }
      ]
    }
  ])
  return { sidebar }
}
