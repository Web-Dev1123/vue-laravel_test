<template>
  <div class="login mt-5">
    <div class="card">
      <div class="card-header">
        Login
      </div>
      <div class="card-body">
        <div class="row text-center mt-5 mb-5">
          <div class="col-12">
              <img class="login_img" src="../../assets/img/login_avatar.png"/>
          </div>
        </div>
        <form>
          <div class="form-group">
            <label for="email">Email address</label>
            <input
              type="email"
              class="form-control"
              :class="{ 'is-invalid': errors.email }"
              id="name"
              v-model="details.email"
              placeholder="Enter email"
            />
            <div class="invalid-feedback" v-if="errors.email">
              {{ errors.email[0] }}
            </div>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              class="form-control"
              :class="{ 'is-invalid': errors.password }"
              id="password"
              v-model="details.password"
              placeholder="Password"
            />
            <div class="invalid-feedback" v-if="errors.password">
              {{ errors.password[0] }}
            </div>
          </div>
           <div class="form-group center">
            <button type="button" @click="login" class="btn btn-primary">
              Login
            </button>
           </div>
        </form>
        <div class="row">
          <div class="col-6 left">
            <router-link class="nav-link font-sm" to="/register"
              >Create Account</router-link>
          </div>
           <div class="col-6 right">
            <router-link class="nav-link font-sm" to="/login"
              >Admin</router-link>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Home",

  data: function() {
    return {
      details: {
        email: null,
        password: null
      }
    };
  },

  computed: {
    ...mapGetters(["errors"])
  },

  mounted() {
    this.$store.commit("setErrors", {});
  },

  methods: {
    ...mapActions("auth", ["sendLoginRequest"]),

    login: function() {
      this.sendLoginRequest(this.details).then(() => {
        this.$router.push({ name: "Home" });
      });
    }
  }
};
</script>
