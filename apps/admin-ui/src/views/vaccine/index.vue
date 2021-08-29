<template>
  <h1>This is vaccine page</h1>
  <el-table :data="tableData" style="width: 100%" max-height="550">
    <el-table-column fixed prop="date" label="Ngày thêm" width="150">
    </el-table-column>

    <el-table-column prop="name" label="Tên" width="200"> </el-table-column>

    <el-table-column prop="city" label="Nước sản xuất" width="200">
    </el-table-column>

    <el-table-column prop="state" label="Ngày sản xuất" width="150">
    </el-table-column>

    <el-table-column prop="address" label="Người thêm" width="200">
    </el-table-column>

    <el-table-column fixed="right" label="Operations" width="200">
      <template #default="scope">
        <el-button
          @click.prevent="deleteRow(scope.$index, tableData)"
          type="text"
          size="small"
        >
          Remove
        </el-button>
        <el-button type="primary" icon="el-icon-edit" circle></el-button>
        <el-button type="danger" icon="el-icon-delete" circle></el-button>
      </template>
    </el-table-column>
  </el-table>
</template>
