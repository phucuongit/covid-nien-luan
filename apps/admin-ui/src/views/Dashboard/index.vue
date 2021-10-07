<script lang="ts">
import { computed, defineComponent } from "vue"
import { Icon } from "@iconify/vue"
import useStats from "./hooks/useStats"

const Dashboard = defineComponent({
  name: "Dashboard",
  components: {
    Icon
  },
  setup() {
    const { data: dataStats, isLoading } = useStats()
    let vietNamPopuplation = 97580000

    const percentInjectedFirst = computed(() => {
      if (!dataStats.value) {
        return 0
      }
      return (
        Math.round(
          (dataStats.value.injected_first_time / vietNamPopuplation +
            Number.EPSILON) *
            10000
        ) / 100
      )
    })
    const dataBI = computed(() => {
      return {
        series: [percentInjectedFirst.value, 100 - percentInjectedFirst.value],
        chartOptions: {
          chart: {
            width: 880,
            type: "donut"
          },
          dataLabels: {
            enabled: true,
            formatter: function (val: number) {
              return val + "%"
            }
          },
          colors: ["#56cc34", "#e43232"],

          labels: ["Đã được tiêm ít nhất một mũi", "Chưa được tiêm"],
          responsive: [
            {
              breakpoint: 480,
              options: {
                chart: {
                  width: 200
                },
                legend: {
                  show: false
                }
              }
            }
          ],
          legend: {
            position: "right",
            offsetY: 0,
            height: 230
          },
          plotOptions: {
            pie: {
              donut: {
                size: "70%"
              }
            }
          }
        }
      }
    })

    const dataInjectByDate = computed(() => {
      return {
        series: [
          {
            name: "Số lượng",
            data: dataStats.value.injected_lastest_7days.map(
              (item) => item.quantity
            )
          }
        ],
        chartOptions: {
          chart: {
            type: "area",
            stacked: false,
            height: 350,
            zoom: {
              type: "x",
              enabled: true,
              autoScaleYaxis: true
            },
            toolbar: {
              autoSelected: "zoom"
            }
          },
          dataLabels: {
            enabled: false
          },
          markers: {
            size: 0
          },
          title: {
            text: "Thống kê số lượng vắc xin tiêm theo ngày",
            align: "left"
          },
          fill: {
            type: "gradient",
            gradient: {
              shadeIntensity: 1,
              inverseColors: false,
              opacityFrom: 0.5,
              opacityTo: 0,
              stops: [0, 90, 100]
            }
          },
          yaxis: {
            title: {
              text: "Số lượng tiêm chủng"
            }
          },
          xaxis: {
            categories: dataStats.value.injected_lastest_7days.map(
              (item) => item.date
            ),
            title: {
              text: "Ngày"
            }
          },
          tooltip: {
            shared: false
          }
        }
      }
    })
    return {
      dataStats,
      ...dataBI.value,
      dataInjectByDate,
      isLoading,
      vietNamPopuplation
    }
  }
})
export default Dashboard
</script>
<template>
  <el-row v-if="isLoading">
    <el-skeleton :rows="6" animated />
  </el-row>
  <div v-else>
    <el-row>
      <el-col :sm="12" :md="12" :lg="6"
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
                <b class="amount">{{
                  Number(dataStats.injected_total_time).toLocaleString()
                }}</b>
                <span class="unit">(mũi)</span>
              </div>
            </div>
          </el-card>
        </div>
      </el-col>
      <el-col :sm="12" :md="12" :lg="6"
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
                <b class="amount">{{
                  Number(dataStats.injected_first_time).toLocaleString()
                }}</b>
                <span class="unit">(mũi)</span>
              </div>
            </div>
          </el-card>
        </div>
      </el-col>
      <el-col :sm="12" :md="12" :lg="6"
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
                <b class="amount">{{
                  Number(dataStats.injected_second_time).toLocaleString()
                }}</b>
                <span class="unit">(mũi)</span>
              </div>
            </div>
          </el-card>
        </div>
      </el-col>
      <el-col :sm="12" :md="12" :lg="6"
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
              <p>Số dân toàn quốc năm 2020</p>
              <div>
                <b class="amount">{{
                  Number(vietNamPopuplation).toLocaleString()
                }}</b>
                <span class="unit">(người)</span>
              </div>
            </div>
          </el-card>
        </div>
      </el-col>
    </el-row>
    <el-row>
      <el-col :sm="24" :lg="12" :span="24" class="chart">
        <el-card
          class="box-card char-box"
          shadow="hover"
          body-style="{background: 'red'}"
        >
          <div class="chart-wrap">
            <p>Tỷ lệ đã tiêm ít nhất 1 mũi trên dân số</p>
            <div id="chart">
              <apexchart
                type="donut"
                width="550"
                :options="chartOptions"
                :series="series"
              ></apexchart>
            </div>
          </div>
        </el-card>
      </el-col>
      <el-col :sm="24" :lg="12" :span="24" class="chart">
        <el-card
          class="box-card char-box"
          shadow="hover"
          body-style="{background: 'red'}"
        >
          <div class="chart-wrap">
            <div id="chart">
              <apexchart
                type="area"
                height="350"
                :options="dataInjectByDate.chartOptions"
                :series="dataInjectByDate.series"
              ></apexchart>
            </div>
          </div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<style lang="scss" scoped>
$primaryColor: #379d1a;
.chart {
  margin-top: 30px;
}
.char-box {
  margin: 0 16px;
  p {
    font-weight: bold;
    font-size: 14px;
    width: 100%;
  }
}
:global(.box_card .el-card__body) {
  display: flex;
  flex-direction: row;
}
.box_card {
  margin: 0 16px;
  @media screen and (max-width: 1199px) {
    margin-bottom: 20px;
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
      font-size: 16px;
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
