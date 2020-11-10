<template>
    <div id="app">
        <img class="background" src="@/assets/background.png" alt="">
        <header class="header">
            <nav class="navbar">
                <div class="logo"><img class="logo-img" src="@/assets/logo.png" alt=""></div>
                <div class="link">
                    <ul>
                        <li><router-link to="/">Login</router-link></li>
                        <li><router-link to="/register">Register</router-link></li>
                    </ul>
                </div>
            </nav>
        </header>
        <slot />
    </div>
</template>

<script>

export default {
    name:'FrontLayout',
}
</script>

<style scoped>

</style>