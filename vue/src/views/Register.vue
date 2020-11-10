<template>
  <FrontLayout>
    <div class="login register">
    <p class="login-title">SIGN UP</p>
        
    <form class="form" @submit.prevent="register">
        <div class="register-container">
          <div class="left-register">
          <p for="first_name" class="label">first name</p>
          <input type="text" name="first_name" class="login-form" autocomplete="none" v-model="first_name"><br>
          <span class="error-message" v-if="isError">{{ errorMessage.first_name }}</span>

          <p for="last_name" class="label">last name</p>
          <input type="text" name="last_name" class="login-form" autocomplete="none" v-model="last_name">
          <span class="error-message" v-if="isError">{{ errorMessage.last_name }}</span>
        </div>

        <div class="right-register">
          <p for="username" class="label">username</p>
          <input type="text" name="username" class="login-form" autocomplete="none" v-model="username">
          <span class="error-message" v-if="isError">{{ errorMessage.username }}</span>

          <p for="password" class="label">password</p>
          <input type="password" name="password" class="login-form" v-model="password">
          <span class="error-message" v-if="isError">{{ errorMessage.password }}</span>
          
        </div>
        </div>
        <button type="submit" class="login-btn">Register</button>
    </form>
    
  </div>

  </FrontLayout>
</template>

<script>

import axios from 'axios'
import FrontLayout from '@/views/layouts/FrontLayout'

export default {
  name: 'Register',
  
  data(){
    return{
      isError: false,
      errorMessage: [],
      first_name:'',
      last_name:'',
      username:'',
      password:'',
      confirmpassword:'',
    }
  },

  methods:{
    async register(){
        await axios.post('http://127.0.0.1:8000/v1/auth/register',{
          first_name:this.first_name,
          last_name:this.last_name,
          username:this.username,
          password:this.password,
        })
        .then(({data})=>{
          this.$router.push('/board/'+ data.token);
        })
        .catch(error => {
        if(error.response){
          this.isError = true;
          this.errorMessage = error.response.data.errors;
        }else if(error.request){
          alert('ini request');
        }
      })
    }
  },

   components:{
      FrontLayout,
    },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>



</style>
