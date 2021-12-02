import axios from "axios";
import router from "./../router";


export default {
  namespaced: true,

  state: {
    userData: null
  },

  getters: {
    user: state => state.userData
  },

  mutations: {
    setUserData(state, user) {
      state.userData = user;
    }
  },

  actions: {
    getUserData({ commit }) {
      axios
        .get(process.env.VUE_APP_API_URL + "user")
        .then(response => {
          commit("setUserData", response.data);
        })
        .catch(() => {
          localStorage.removeItem("authToken");
        });
    },
    sendLoginRequest({ commit }, data) {
      commit("setErrors", {}, { root: true });
      return axios
        .post(process.env.VUE_APP_API_URL + "login", data)
        .then(response => {
          commit("setUserData", response.data.user);
          localStorage.setItem("userData", JSON.stringify(response.data.user));
          localStorage.setItem("authToken", response.data.token);
        });
    },
    sendRegisterRequest({ commit }, data) {
      commit("setErrors", {}, { root: true });
      return axios
        .post(process.env.VUE_APP_API_URL + "register", data)
        .then(response => {
          commit("setUserData", response.data.user);
          localStorage.setItem("userData", JSON.stringify(response.data.user));
          localStorage.setItem("authToken", response.data.token);
        });
    },
    sendLogoutRequest({ commit }) {
      axios.post(process.env.VUE_APP_API_URL + "logout").then(() => {
        commit("setUserData", null);
        localStorage.removeItem("authToken");
        localStorage.removeItem("userData");
        router.push({ name: "Login" });
      });
    },
    uploadIamge({ commit }, data) {
      commit("setErrors", {}, { root: true });
      return axios
        .post(process.env.VUE_APP_API_URL + "uploadimage", data)
        .then(response => {
          return response.data;
        });
    },
    getImageList({ commit }, data) {
      commit("setErrors", {}, { root: true });
      return axios
        .get(process.env.VUE_APP_API_URL + "getimages/"+ data.credentialType + "/" + data.userId)
        .then(response => {
          return response.data;
        });
    },
    getAllImageList({ commit }) {
      commit("setErrors", {}, { root: true });
      return axios
        .get(process.env.VUE_APP_API_URL + "getallimages")
        .then(response => {
          return response.data;
        });
    },
    deleteImg({ commit }, data) {
      commit("setErrors", {}, { root: true });
      return axios
        .post(process.env.VUE_APP_API_URL + "deleteimg", data)
        .then(response => {
          return response.data;
        });
    },
  }
};
