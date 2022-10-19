import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/home',
            name: 'home',
            component: () => import('./Components/Home.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/registration',
            name: 'registration',
            component: () => import('./Components/Registration.vue'),
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./Components/Login.vue'),
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/user',
            name: 'user',
            component: () => import('./Components/UserProfile.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/plans',
            name: 'plans',
            component: () => import('./Components/Payment/Plans.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/subscription',
            name: 'subscription',
            component: () => import('./Components/Payment/Subscription.vue'),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '/balance',
            name: 'balance',
            component: () => import('./Components/Payment/Balance.vue'),
            meta: {
                requiresAuth: true
            }
        },
    ]
})

router.beforeEach((to, from) => {
    if (to.meta.requiresAuth && !localStorage.getItem('token')) {
        return { name : 'login' }
    }
    if (to.meta.requiresAuth == false && localStorage.getItem('token')) {
        return { name : 'dashboard' }
    }
})

export default router
