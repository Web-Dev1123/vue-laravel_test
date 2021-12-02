import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const guest = (to, from, next) => {
  if (!localStorage.getItem("authToken")) {
    return next();
  } else {
    return next("/");
  }
};
// const auth = (to, from, next) => {
//   if (localStorage.getItem("authToken")) {
//     return next();
//   } else {
//     return next("/login");
//   }
// };

const routes = [
  {
    path: "/",
    name: "Home",
    component: () => import(/* webpackChunkName: "home" */ "../views/Home.vue")
  },
  {
    path: "/credential",
    name: "Credential",
    component: () => import(/* webpackChunkName: "home" */ "../views/Credential.vue")
  },
  {
    path: "/login",
    name: "Login",
    beforeEnter: guest,
    component: () =>
      import(/* webpackChunkName: "login" */ "../views/Auth/Login.vue")
  },
  {
    path: "/register",
    name: "Register",
    beforeEnter: guest,
    component: () =>
      import(/* webpackChunkName: "register" */ "../views/Auth/Register.vue")
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

export default router;
