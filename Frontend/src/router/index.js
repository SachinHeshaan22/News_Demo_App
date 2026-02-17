import { createRouter, createWebHistory } from 'vue-router';
import NewsDashboard from '../views/NewsDashboard.vue';
import CreateNews from '../views/CreateNews.vue';
import EditNews from '../views/EditNews.vue';

const routes = [
    {
        path: '/',
        name: 'Dashboard',
        component: NewsDashboard,
        meta: {
            title: 'News Dashboard',
        },
    },
    {
        path: '/create',
        name: 'CreateNews',
        component: CreateNews,
        meta: {
            title: 'Create News',
        },
    },
    {
        path: '/edit/:id',
        name: 'EditNews',
        component: EditNews,
        meta: {
            title: 'Edit News',
        },
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


router.beforeEach((to, from, next) => {
    document.title = to.meta.title || 'News Dashboard';
    next();
});

export default router;
