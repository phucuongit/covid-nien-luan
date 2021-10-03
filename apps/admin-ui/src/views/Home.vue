<script lang="ts">
import { defineComponent } from "vue"
import { Icon } from "@iconify/vue"

export default defineComponent({
  components: {
    Icon
  }
})
</script>

<template>
  <div class="home">
    <el-container>
      <el-header class="bg-11385e">
        <div class="header container">
          <div class="title">
            <Icon
              icon="carbon:manage-protection"
              color="white"
              width="25px"
              class="mr-5"
            />
            Quản lý xét nghiệm & tiêm phòng Covid-19
          </div>
        </div>
      </el-header>
      <el-main>
        <div class="main container">
          <el-row :gutter="30" class="text-center">
            <el-col :md="6" :sm="12" :xs="24">
              <el-card class="box-card box_card" shadow="hover">
                <div class="icon">
                  <Icon
                    icon="carbon:manage-protection"
                    color="green"
                    width="40px"
                  />
                </div>
                <div class="title_summary">
                  <p>Số mũi đã tiêm toàn quốc</p>
                  <div>
                    <b class="amount">12345678 </b>
                    <span class="unit">(mũi)</span>
                  </div>
                </div>
              </el-card>
            </el-col>
            <el-col :md="6" :sm="12" :xs="24">
              <el-card class="box-card box_card" shadow="hover">
                <div class="icon">
                  <Icon
                    icon="carbon:manage-protection"
                    color="green"
                    width="40px"
                  />
                </div>
                <div class="title_summary">
                  <p>Số mũi đã tiêm toàn quốc</p>
                  <div>
                    <b class="amount">12345678 </b>
                    <span class="unit">(mũi)</span>
                  </div>
                </div>
              </el-card>
            </el-col>
            <el-col :md="6" :sm="12" :xs="24">
              <el-card class="box-card box_card" shadow="hover">
                <div class="icon">
                  <Icon
                    icon="carbon:manage-protection"
                    color="green"
                    width="40px"
                  />
                </div>
                <div class="title_summary">
                  <p>Số mũi đã tiêm toàn quốc</p>
                  <div>
                    <b class="amount">12345678 </b>
                    <span class="unit">(mũi)</span>
                  </div>
                </div>
              </el-card>
            </el-col>
            <el-col :md="6" :sm="12" :xs="24">
              <el-card class="box-card box_card" shadow="hover">
                <div class="icon">
                  <Icon
                    icon="carbon:manage-protection"
                    color="green"
                    width="40px"
                  />
                </div>
                <div class="title_summary">
                  <p>Số mũi đã tiêm toàn quốc</p>
                  <div>
                    <b class="amount">12345678 </b>
                    <span class="unit">(mũi)</span>
                  </div>
                </div>
              </el-card>
            </el-col>
          </el-row>
        </div>
      </el-main>
      <el-footer class="pd-0">
        <div class="text-center pt-15">
          @2021 - nhóm 1 - Website quản lý xét nghiệm và tiêm phòng Covid-19
        </div>
      </el-footer>
    </el-container>
  </div>
</template>

<style scoped>
.home .el-header {
  position: sticky;
  top: 0;
  z-index: 100;
}

.header {
  padding: 18px 0px;
}

.title {
  color: white;
  font-size: 18px;
  font-weight: bold;
  display: flex;
  align-items: center;
}

.home .main {
  min-height: 700px;
}

.el-footer {
  border-top: 1px solid #ddd;
  --el-footer-height: 50px;
  position: sticky;
  bottom: 0px;
  background: #fff;
  z-index: 10;
}
</style>
