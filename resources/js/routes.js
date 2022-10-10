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
