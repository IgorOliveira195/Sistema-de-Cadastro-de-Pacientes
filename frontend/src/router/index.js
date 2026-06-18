import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'
import AppLayout from '@/components/AppLayout.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/LoginView.vue'),
    meta: { guest: true }
  },
  {
    path: '/',
    component: AppLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        redirect: '/dashboard'
      },
      {
        path: 'dashboard',
        name: 'dashboard',
        component: () => import('@/views/Dashboard.vue')
      },
      {
        path: 'enderecos',
        name: 'enderecos.index',
        component: () => import('@/views/enderecos/Index.vue')
      },
      {
        path: 'enderecos/novo',
        name: 'enderecos.create',
        component: () => import('@/views/enderecos/Form.vue')
      },
      {
        path: 'enderecos/:id',
        name: 'enderecos.edit',
        component: () => import('@/views/enderecos/Form.vue'),
        props: true
      },
      {
        path: 'pacientes',
        name: 'pacientes.index',
        component: () => import('@/views/pacientes/Index.vue')
      },
      {
        path: 'pacientes/novo',
        name: 'pacientes.create',
        component: () => import('@/views/pacientes/Form.vue')
      },
      {
        path: 'pacientes/:id',
        name: 'pacientes.edit',
        component: () => import('@/views/pacientes/Form.vue'),
        props: true
      }
    ]
  }
]

const router = new VueRouter({
  routes
})

router.beforeEach(async (to, from, next) => {
  if (!store.getters['auth/user'] && store.getters['auth/isAuthenticated']) {
    try {
      await store.dispatch('auth/fetchMe')
    } catch {
      store.dispatch('auth/clearSession')
      return next({ name: 'login' })
    }
  }

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.getters['auth/isAuthenticated']) {
      return next({ name: 'login' })
    }
  }

  if (to.matched.some((record) => record.meta.guest)) {
    if (store.getters['auth/isAuthenticated']) {
      return next({ name: 'dashboard' })
    }
  }

  next()
})

export default router
