<template>
  <b-card no-body class="shadow p-3 d-flex flex-row align-items-center mb-3">

    <div class="mr-2  rounded px-3 text-light" :class="bgColor">
      <i v-if="title == 'Total Units'" class="ri-contrast-2-line ri-3x"></i>
      <i v-if="title == 'Active'" class="ri-truck-line ri-3x"></i>
      <i v-if="title == 'Idle'" class="ri-alert-line ri-3x"></i>
      <i v-if="title == 'Stop'" class="ri-stop-circle-line ri-3x"></i>
      <i v-if="title == 'Offline'" class="ri-cloud-off-line ri-3x"></i>
    </div>
    <div class="text-center w-100">

      <div class="h2 mb-0 font-weight-bold">{{value}}</div>
      <small class="font-weight-bold">{{title}}</small>
    </div>
  </b-card>
</template>
<script>
  export default {
    name: 'SummaryCard',
    props: ['value', 'title'],
    data: function() {
      return {}
    },
    created() {},
    computed: {
      bgColor() {
        return this.title == 'Total Units' ? 'bg-primary' :
          this.title == 'Active' ? 'bg-success' :
          this.title == 'Idle' ? 'bg-warning' :
          this.title == 'Stop' ? 'bg-dark' :
          this.title == 'Offline' ? 'bg-danger' : ''
      },
    },
    methods: {},
  }
</script>
<style>
</style>
