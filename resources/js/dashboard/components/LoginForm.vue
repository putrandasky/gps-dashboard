<template>
  <b-form @submit="submit" class="mt-2">
    <b-alert :variant="alert.status" :show="alert.status != ''">
      {{alert.message}}
    </b-alert>
    <b-form-group class="position-relative" :invalid-feedback="errors.email" :state="stateEmail">
      <b-form-input type="email" class="form-control pl-5" placeholder="Email" v-model="input.email" :state="stateEmail" autocomplete="on"></b-form-input>
      <i class="ri-mail-line position-absolute text-secondary" style="top:8px;left:18px"></i>
    </b-form-group>
    <b-form-group class="position-relative" :invalid-feedback="errors.password" :state="statePassword">
      <b-form-input class="form-control pl-5" placeholder="Password" v-model="input.password" :state="statePassword"></b-form-input>
      <i class="ri-key-2-line position-absolute text-secondary" style="top:8px;left:18px"></i>

    </b-form-group>
    <div class=" mt-3 ">
      <b-btn type="submit" variant="primary" block class="rounded">Login</b-btn>
    </div>

    <b-overlay variant="dark" :show="isLoading" blur="" fixed no-wrap></b-overlay>
  </b-form>
</template>
<script>
  export default {
    name: 'LoginForm',
    data: function() {
      return {
        input: {
          email: '',
          password: '',
        },
        errors: {
          email: '',
          password: '',
        },
        isLoading: false,
        alert: {
          status: '',
          message: ''
        }
      }
    },
    created() {

    },
    computed: {

      stateEmail() {
        return this.errors.email == 'no-error' ? true : this.errors.email ? false : null
      },

      statePassword() {
        return this.errors.password == 'no-error' ? true : this.errors.password ? false : null
      },
    },
    methods: {
      submit(e) {
        e.preventDefault()

        let self = this
        this.isLoading = true
        this.$emit('isLoading', true)

        axios.post(`api/login`, this.input)
          .then((response) => {
            console.log(response.data)
            self.isLoading = false
            let input = {
              email: '',
              password: '',
            }
            self.input = input
            localStorage.user_api_hash = response.data.user_api_hash
            // this.$emit('isLoading', false)
            this.$emit('loggedIn', true)
          })
          .catch((error) => {
            this.$emit('isLoading', false)

            self.alert.status = 'danger'
            self.alert.message = 'Wrong Credentials'

          })
        // setTimeout(() => {
        // this.isLoading = false
        // this.submitedModal = true
        // }, 1000);
      }
    },
  }
</script>
<style>
</style>
