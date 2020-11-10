<template>

    <FrontLayout>
      <div class="login">
        <p class="login-title">SIGN IN</p>
        <form class="form" @submit.prevent="login">
            <p for="username" class="label">username</p>
              <input type="text" name="username" class="login-form" autocomplete="none" v-model="username">
              <span class="error-message" v-if="isErrorRequired">{{ errorMessageRequired.errors.username }}</span>
            <p for="password" class="label">password</p>
              <input type="password" name="password" class="login-form" v-model="password">
              <span class="error-message" v-if="isErrorRequired">{{ errorMessageRequired.errors.password }}</span><br>

          <span class="error-message" v-if="isErrorRequired">{{ errorMessageRequired.message }}</span>
          
          <button type="submit" class="login-btn">Login</button>
        </form>
          <span class="error-message" v-if="isErrorUsernamePassword">{{ errorMessageUsernamePassword }}</span>
      </div>

    </FrontLayout>
</template>

<script>

import axios from 'axios'
import FrontLayout from '@/views/layouts/FrontLayout'

export default({
    name: 'Login',

    data(){
        return{
            token : null,
            isErrorRequired: false,
            isErrorUsernamePassword: false,
            errorMessageRequired: [],
            errorMessageUsernamePassword: '',
            username:'',
            password:'',
        }
    },

    methods:{
      async login(){
          await axios.post('http://127.0.0.1:8000/v1/auth/login',{
            username: this.username,
            password: this.password,
          })
          .then(({data})=>{
            this.token = data.token;
            this.$router.push('/board/'+ data.token);
          })
          .catch(error =>{
            if(error.response){
              console.log(error.response.data);
              if(typeof error.response.data.errors === 'undefined' )
              {
                this.isErrorRequired = false;
                this.errorMessageRequired = [];
                this.isErrorUsernamePassword = true;
                this.errorMessageUsernamePassword = error.response.data.message;
              }
              else{
                this.isErrorRequired = true;
                this.errorMessageRequired = error.response.data;
              }
              
            }else if(error.request){
              alert('ini request');
            }else{
              alert('somethings wrong');
            }
          })
      }
    },

    components:{
      FrontLayout
    }
})
</script>

<style scoped>

</style>