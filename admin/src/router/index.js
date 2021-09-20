import { createRouter, createWebHashHistory } from 'vue-router'
import Home from '../views/Home.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    components: {
      default: Home,
    },
    children: [
      {
        name: "Main",
        path: '/main',
        components: {
          home: () => import('../views/Main.vue'),
        }
      },
      {
        name: "Teams",
        path: '/teams',
        components: {
          home: () => import('../views/Teams.vue'),
        }
      }
    ]
  },
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
