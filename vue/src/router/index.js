import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path:'/',
    name:'Login',
    meta:{
      title:'Login Page'
    },
    component: () => import('../views/Login')
  },
  {
    path:'/register',
    name:'Register',
    meta:{
      title:'Register Page'
    },
    component: () => import('../views/Register')
  },
  {
    path:'/board/:token',
    name:'Boards',
    meta:{
      title:'Boards',
    },
    component: () => import('../views/admin/Boards')
  },
  {
    path:'/board/:board_id/:token',
    name:'Board',
    meta:{
      title:'Board',
    },
    component: () => import('../views/admin/Board')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to,from,next) => { 
  document.title = to.meta.title;
  next();
})

export default router
