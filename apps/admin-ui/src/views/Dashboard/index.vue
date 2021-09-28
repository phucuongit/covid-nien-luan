<script lang="ts">
import { defineComponent, provide, ref } from "vue"
import { Icon } from "@iconify/vue"

const Dashboard = defineComponent({
  name: "Dashboard",
  components: {
    Icon
  },
  data() {
    return {
      title: "",
      points: [10, 0, 8, 2, 6, 4, 5, 5],
      chartType: "Spline",
      seriesColor: "#6fcd98",
      colorInputIsSupported: null,
      chartOptions: {
        chart: {
          type: "spline"
        },
        title: {
          text: "Sin chart"
        },
        series: [
          {
            data: [10, 0, 8, 2, 6, 4, 5, 5],
            color: "#6fcd98"
          }
        ]
      }
    }
  }
  //   setup() {
  //     return ""
  //   }
})
export default Dashboard
</script>
<template>
  <el-row>
    <el-col :span="6"
      ><div class="grid-content bg-purple">
        <el-card
          class="box-card box_card"
          shadow="hover"
          body-style="{background: 'red'}"
        >
          <div class="icon">
            <Icon icon="carbon:manage-protection" />
          </div>
          <div class="title_summary">
            <p>Số mũi đã tiêm toàn quốc</p>
            <div>
              <b class="amount">40,211,442 </b>
              <span class="unit">(mũi)</span>
            </div>
          </div>
        </el-card>
      </div>
    </el-col>
    <el-col :span="6"
      ><div class="grid-content bg-purple">
        <el-card
          class="box-card box_card"
          shadow="hover"
          body-style="{background: 'red'}"
        >
          <div class="icon">
            <Icon icon="carbon:manage-protection" />
          </div>
          <div class="title_summary">
            <p>Số người chỉ tiêm được một mũi</p>
            <div>
              <b class="amount">40,211,442 </b>
              <span class="unit">(mũi)</span>
            </div>
          </div>
        </el-card>
      </div>
    </el-col>
    <el-col :span="6"
      ><div class="grid-content bg-purple">
        <el-card
          class="box-card box_card"
          shadow="hover"
          body-style="{background: 'red'}"
        >
          <div class="icon">
            <Icon icon="carbon:manage-protection" />
          </div>
          <div class="title_summary">
            <p>Số người đã được tiêm đủ hai mũi</p>
            <div>
              <b class="amount">40,211,442 </b>
              <span class="unit">(mũi)</span>
            </div>
          </div>
        </el-card>
      </div>
    </el-col>
    <el-col :span="6"
      ><div class="grid-content bg-purple">
        <el-card
          class="box-card box_card"
          shadow="hover"
          body-style="{background: 'red'}"
        >
          <div class="icon">
            <Icon icon="carbon:manage-protection" />
          </div>
          <div class="title_summary">
            <p>Số dân toàn quốc</p>
            <div>
              <b class="amount">40,211,442 </b>
              <span class="unit">(người)</span>
            </div>
          </div>
        </el-card>
      </div>
    </el-col>
  </el-row>
  <el-row>
    <el-col :span="24" class="chart">
      <el-card
        class="box-card box_card"
        shadow="hover"
        body-style="{background: 'red'}"
      >
        <Chart />
      </el-card>
    </el-col>
  </el-row>
</template>

<style lang="scss" scoped>
$primaryColor: #379d1a;
.chart {
  margin-top: 30px;
}
.box_card {
  margin: 0 16px;
  :global(.el-card__body) {
    display: flex;
    flex-direction: row;
  }
  .icon svg {
    width: 30px;
    height: 30px;
    color: $primaryColor;
  }
  .title_summary {
    padding-left: 10px;
    p {
      margin: 0;
      font-weight: bold;
      font-size: 18px;
    }
    .amount {
      font-size: 28px;
      cursor: pointer;
      padding-top: 8px;
      transition: all 0.2s ease-in;
      display: inline-block;
      &:hover {
        color: $primaryColor;
      }
    }
    .unit {
      font-style: italic;
      font-weight: bold;
      margin-left: 8px;
    }
  }
}
</style>
