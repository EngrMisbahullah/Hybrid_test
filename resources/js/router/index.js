import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../store/auth";
const routes = [
    {
        path: "/login",
        component: () => import('../components/auth/Login.vue'),
        name : 'login',
    },
    {
        path:"/",
        component: () => import('../components/Task/Index.vue'),
        name : "home",
        meta: {
            auth: true,
        },
    },
    {
        path:"/add/task",
        component: () => import('../components/Task/AddTask.vue'),
        name : "AddTask",
        meta: {
            auth: true,
        },
    },
    {
        path: "/edit/task/:id", // Define a dynamic segment :id
        component: () => import('../components/Task/EditTask.vue'),
        name: "EditTask", // Give it a name
        meta: {
          auth: true,
        },
    },
    { path: "/:pathMatch(.*)*", component: () => import('../components/notfounds/PageNotFound.vue') }


];
const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, form, next) => {
    const authStore = useAuthStore();
    if (to.meta.auth && !authStore.isAuthenticated ) {
        authStore.setLastClickedUrl(to.fullPath);
        next({ name: "login" });
    }
    if (to.meta.guest && authStore.isAuthenticated ) {
        next({ name: "home" });
    }
    if(to.name === 'setToken'){
        authStore.setToken(to.params.token);
        userDetails();
        async function userDetails() {
            let response = await authStore.userDetails();
            if (response) {
                if (!response.success) {
                    if (response.errors) {
                        for (let key in response.errors) {
                            let error = response.errors[key];
                            v$.value[key].$serverError = error
                        }
                    }
                    else if (response.message) {
                        alert(response.message);
                    }
                }else{

                    authStore.setUser(response.data.user)
                    router.push({name: 'home'});
                }
            }
        };
    }
    next();
});


export default router;
