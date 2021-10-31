<template>
  <div class="h-100">
    <b-overlay variant="dark" :show="isLoading" blur="" fixed no-wrap></b-overlay>
    <transition name="fade" mode="out-in">

      <div v-if="isAuth" class="bg-dark d-block" style="height: 260px; top: 0px; left: 0px; right: 0px; z-index: 0; position: absolute;"></div>
    </transition>
    <transition name="fade" mode="out-in">
      <b-container v-if="isAuth" class="pt-3">
        <b-row>
          <b-col sm="12" class="text-right mb-3">
            <b-btn variant="outline-danger" size="sm" @click="logout"><i class="ri-shut-down-line align-bottom"></i> Log Out</b-btn>
          </b-col>
          <b-col sm="12" class="d-md-flex justify-content-md-between">
            <div class="h1 font-weight-bold text-light mt-2 mb-5">GPS Dashboard</div>


            <div class="bg-white rounded shadow py-1 px-3 mx-auto mx-md-0  mb-3 d-flex justify-content-center align-items-center" style="width:fit-content">

              <span class="mr-3 pr-3 border-right border-secondary">

                <strong>Start Date</strong>
                <div class="d-flex justify-content-between align-items-center">
                  <!-- <span class="mr-2">{{date.start | dateFormated}}</span> -->
                  <b-form-datepicker size="sm" id="from_date" :date-format-options="{ year: 'numeric', month: 'short', day: 'numeric' }" v-model="date.start" :max="maxDateStartFormated" class="mb-2"></b-form-datepicker>
                </div>
              </span>
              <span class="mr-3">

                <strong>End Date</strong>
                <div class="d-flex justify-content-between align-items-center">
                  <!-- <span class="mr-2">{{date.end | dateFormated}}</span> -->

                  <b-form-datepicker size="sm" id="to_date" :date-format-options="{ year: 'numeric', month: 'short', day: 'numeric' }" v-model="date.end" :min="minDateFormated" :max="maxDateEndFormated" class="mb-2"></b-form-datepicker>
                </div>
              </span>
              <span>

                <b-btn variant="success" size="sm" block pill @click="getData">Update</b-btn>
              </span>

            </div>
          </b-col>
        </b-row>
        <transition name="fade" mode="out-in">
          <b-row class="mb-3 justify-content-center mt-sm-5" v-if="!isLoading">
            <!-- <b-col md="2" sm="6">
            <b-card no-body class="shadow p-3  mb-3">
              <label for="from_date">From</label>
              <div class="d-flex justify-content-between">
                <span>{{date.start | dateFormated}}</span>
                <b-form-datepicker size="sm" button-only id="from_date" v-model="date.start" :max="maxDateStartFormated" class="mb-2"></b-form-datepicker>
              </div>
              <label for="to_date">to</label>
              <div class="d-flex justify-content-between">
                <span>{{date.end | dateFormated}}</span>

                <b-form-datepicker size="sm" button-only id="to_date" v-model="date.end" :min="minDateFormated" :max="maxDateEndFormated" class="mb-2"></b-form-datepicker>
              </div>
              <b-btn variant="success" size="sm" block pill @click="getData">Update</b-btn>
            </b-card>
          </b-col>  -->
            <!-- <b-col col>
            <summary-card :value="4" title="Total Units"></summary-card>
          </b-col>
          <b-col col>
            <summary-card :value="2" title="Active"></summary-card>

          </b-col>
          <b-col col>
            <summary-card :value="3" title="Idle"></summary-card>

          </b-col>
          <b-col col>
            <summary-card :value="4" title="Stop"></summary-card>

          </b-col>
          <b-col col>
            <summary-card :value="1" title="Offline"></summary-card>

          </b-col> -->
            <b-col col>
              <summary-card :value="data.summary.total" title="Total Units"></summary-card>
            </b-col>
            <b-col col>
              <summary-card :value="data.summary.active" title="Active"></summary-card>

            </b-col>
            <b-col col>
              <summary-card :value="data.summary.idle" title="Idle"></summary-card>

            </b-col>
            <b-col col>
              <summary-card :value="data.summary.stop" title="Stop"></summary-card>

            </b-col>
            <b-col col>
              <summary-card :value="data.summary.offline" title="Offline"></summary-card>

            </b-col>
          </b-row>
        </transition>
        <transition name="fade" mode="out-in">
          <b-row v-if="!isLoading">
            <b-col lg="4" md="6" class="mb-3">
              <div class="h2 font-weight-bold mb-4">Units</div>
              <list-card v-for="v,i in data.devices" :key="i" :name="v.name" :sensors="v.sensors" :iconColor="v.icon_color"></list-card>
            </b-col>
            <b-col lg="4" md="6" class=" mb-3">
              <div class="h2 font-weight-bold mb-4">Running Hours</div>

              <b-card class="shadow">
                <multi-bar :height="300" :chartLabel=" data.date2 " :chartDatasets="data.hour_meter.data_set " keyChartDatasets="chartData_increment" yLabel="Hours" xLabel="Date" />

              </b-card>
            </b-col>
            <b-col lg="4" class="mb-3">
              <div class="h2 font-weight-bold mb-4">Distance</div>

              <b-card class="shadow">
                <multi-bar :height="300" :chartLabel=" data.date2 " :chartDatasets="data.odometer.data_set " keyChartDatasets="chartData_increment" yLabel="km" xLabel="Date" />

              </b-card>
            </b-col>
            <b-col lg="12" class="mb-3">
              <div class="h2 font-weight-bold mb-4">Fuel Consumed</div>

              <b-card class="shadow">
                <multi-bar :height="300" :chartLabel=" data.date2 " :chartDatasets="data.fuel_consumed.data_set " keyChartDatasets="chartData_increment" yLabel="Litre" xLabel="Date" />

              </b-card>
            </b-col>
            <b-col lg="12" class="mb-3">
              <div class="h2 font-weight-bold mb-4">Max Performance</div>

              <b-card class="shadow">
                <multi-bar :height="300" :chartLabel=" data.date2 " :chartDatasets="data.engine_temperature.data_set " yLabel="Celcius" xLabel="Date" />

              </b-card>
            </b-col>
          </b-row>
        </transition>
      </b-container>
    </transition>
    <transition name="fade" mode="out-in">

      <b-row v-if="modalLoginOpen" class="w-100 mx-0 h-100 d-flex justify-content-center align-items-center">
        <b-col lg="3">

          <b-card class="shadow">
            <div>
              <strong>
                Login Form
              </strong>
            </div>
            <small>Please login with registered Amtiss account</small>

            <login-form @isLoading="isLoading = $event" @loggedIn="handleLoggedIn"></login-form>
          </b-card>
        </b-col>
      </b-row>
    </transition>
  </div>
</template>
<script>
  import MultiBar from "../components/MultiBar.vue";
  import Navbar from "../components/Navbar.vue"
  import SummaryCard from "../components/SummaryCard.vue"
  import ListCard from "../components/ListCard.vue"
  import LoginForm from "../components/LoginForm.vue"
  export default {
    name: 'Dashboard',
    components: {
      MultiBar,
      Navbar,
      SummaryCard,
      ListCard,
      LoginForm
    },
    data: function() {
      return {
        modalLoginOpen: true,
        isAuth: false,
        isLoading: false,
        data: {},
        date: {
          start: null,
          end: null,
          max: null
        }
      }
    },
    created() {
      this.date.end = dayjs(new Date()).format('YYYY-MM-DD')
      this.date.start = dayjs(new Date()).subtract(7, 'day').format('YYYY-MM-DD')
      if (localStorage.user_api_hash) {
        this.getData()
        this.modalLoginOpen = false
        this.isAuth = true
        console.log('has api');
      }
    },
    computed: {
      minDateFormated() {
        return dayjs(this.date.start).format('YYYY-MM-DD')
      },
      maxDateStartFormated() {
        return dayjs(this.date.end).format('YYYY-MM-DD')
      },
      maxDateEndFormated() {
        return dayjs(new Date()).format('YYYY-MM-DD')
      }
    },
    methods: {
      logout() {
        this.modalLoginOpen = true
        this.isAuth = false
        localStorage.removeItem('user_api_hash')
      },
      handleLoggedIn() {
        console.log('login');
        this.modalLoginOpen = false
        this.$bvToast.toast('Please wait while we fetching the data', {
          title: `LOGGED IN`,
          variant: 'success',
          solid: true
        })
        setTimeout(() => {

          this.isAuth = true

          this.getData()
        }, 500);
      },
      getData() {
        let date_end = dayjs(this.date.end).format('YYYY-MM-DD')
        let date_start = dayjs(this.date.start).format('YYYY-MM-DD')
        this.isLoading = true
        axios.get(`api/dashboard?date_end=${date_end}&date_start=${date_start}&user_api_hash=${localStorage.user_api_hash}`)
          .then((response) => {
            console.log(response.data)
            this.data = response.data
            this.isLoading = false

          })
          .catch((error) => {
            console.log(error);
            this.$bvToast.toast('Please Try Again', {
              title: `Ooops.. Something Wrong`,
              variant: 'danger',
              solid: true
            })
          })
      }
    },
  }
</script>
<style>
  .slide-shrink-fade-enter-active {
    transition: all 0.5s ease;
  }

  ∂ .slide-shrink-fade-leave-active {
    transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);
  }

  .slide-shrink-fade-enter,
  .slide-shrink-fade-leave-to {
    /* transform: translateX(10px); */
    opacity: 0;
    /* height: 0%; */
    font-size: 99%;
  }
</style>
