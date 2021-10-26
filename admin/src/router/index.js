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
        name: "Recurring",
        path: '/recurring',
        components: {
          home: () => import('../views/RecurringTasks.vue')
        }
      },
      {
        name: "Teams",
        path: '/teams',
        components: {
          home: () => import('../views/Teams.vue'),
        }
      },
      {
        name: "Team",
        path: "/team",
        components: {
          home: () => import('../views/Team.vue'),
        }
      },
      {
        name: "Projects",
        path: "/projects",
        components: {
          home: () => import('../views/Projects.vue')
        }
      },
      {
        name: "ProjectDetails",
        path: "/projectDetails/:id",
        components: {
          home: () => import('../views/ProjectDetails.vue'),
        },
        props: true,
      },
      {
        name: "DiscusionBoard",
        path: "/discustionboard",
        components: {
          home: () => import('../views/DiscutionBoards.vue')
        },
      },
      {
        name: "DB_Topics",
        path: "/db_topic/:topic_id",
        components: {
          home: () => import('../views/Topics.vue')
        },
        props: true,
      },
      {
        name: "Add_Topic",
        path: "/db_topic/addTopic/:topic_id",
        components: {
          home: () => import('../views/AddTopic.vue')
        },
        props: true,
      },
      {
        name: "Topic",
        path: '/db_topic/topic/:topic_id',
        components: {
          home: () => import('../views/Topic/Topic.vue')
        },
        props: true,
      },
      {
        name: "Issues",
        path: "/issues",
        components: {
          home: () => import('../views/Issues.vue')
        }
      }
    ]
  },
  {
    path: "/login",
    name: "Login",
    component: () => import('../views/Login.vue'),
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
