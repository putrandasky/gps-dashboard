<template>
  <div class="mb-2">
    <b-card no-body class="shadow">
      <div class="py-2 px-3 d-flex justify-content-between">
        <span>
          {{name}}
          <small>
            <b-badge :variant="getBadgeColor(iconColor)">{{getBadgeText(iconColor)}}</b-badge>
          </small>
        </span>
        <span>
          <i v-if="sensors.odometer.id != null" class="ri-dashboard-2-line" v-b-tooltip.hover title="Odometer"></i>
          <i v-if="sensors.hour_meter.id != null" class="ri-settings-3-line" v-b-tooltip.hover title="Hour Meter"></i>
          <i v-if="sensors.fuel_consumed.id != null" class="ri-oil-line" v-b-tooltip.hover title="Fuel Consumed"></i>
          <i v-if="sensors.engine_temperature.id != null" class="ri-temp-hot-fill" v-b-tooltip.hover title="Engine Temperature"></i>
        </span>
      </div>
    </b-card>
  </div>

</template>
<script>
  export default {
    name: 'ListCard',
    props: ['name', 'sensors', 'iconColor'],
    data: function() {
      return {}
    },
    created() {},
    methods: {
      getBadgeColor(color) {
        return color == 'green' ? 'success' :
          color == 'black' ? 'dark' :
          color == 'yellow' ? 'warning' :
          color == 'red' ? 'danger' : 'secondary'
      },
      getBadgeText(color) {
        return color == 'green' ? 'Active' :
          color == 'black' ? 'Stopped' :
          color == 'yellow' ? 'Idle' :
          color == 'red' ? 'Offline' : ''
      }
    },
  }
</script>
<style>
</style>
