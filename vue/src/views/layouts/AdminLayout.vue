<template>
    <div id="app">
        <header class="header">
            <nav class="navbar">
                <div class="logo"><img class="logo-img" src="@/assets/logo.png" alt=""></div>
                <div class="link">
                    <ul>
                        <li><router-link v-bind:to="'/board/' + $route.params.token">Boards</router-link></li>
                        <li><button class="logoutbtn" @click.stop="logout">Logout</button></li>
                    </ul>
                </div>
            </nav>
        </header>
        <slot />
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name:'AdminLayout',

    methods:{

        async logout(){
            await axios.get('http://localhost:8000/v1/auth/logout/'+ this.$route.params.token).then(()=>{
            this.$router.push('/');
            })
        }
    }
}
</script>

<style scoped>

</style>