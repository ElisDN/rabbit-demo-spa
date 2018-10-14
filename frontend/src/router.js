import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'

Vue.use(Router);

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./views/Login.vue')
    },
    {
      path: '/signup',
      name: 'signup',
      component: () => import('./views/SignUp/Request.vue')
    },
    {
      path: '/signup/confirm',
      name: 'signup.confirm',
      component: () => import('./views/SignUp/Confirm.vue')
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('./views/Profile/Show.vue')
    },
    {
      path: '/author',
      name: 'author',
      component: () => import('./views/Author/Show.vue')
    },
    {
      path: '/author/create',
      name: 'author.create',
      component: () => import('./views/Author/Create.vue')
    },
    {
      path: '/author/upload',
      name: 'author.upload',
      component: () => import('./views/Author/Video/Upload.vue')
    },
    {
      path: '/author/video/:id',
      name: 'author.video',
      component: () => import('./views/Author/Video/Show.vue')
    }
  ]
})
