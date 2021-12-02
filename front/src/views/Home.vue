<template>


  <div class="container" v-if="user.is_admin != 1">
    <div class="row">
      <div class="col-6"><span style="font-weight: bold;">{{ user.name }}</span> </div>
      <div class="col-6 right"><a class="nav-link" style="padding:0"  href="#"  @click="logout">Logout</a></div>
    </div>
    <div class="row mt-5" @click="goToCredential(1)">
      <div class="col-12">
        <div class="card">
          <div class="row text-center mt-5">
            <div class="col-12">
               <img class="credential_img" src="../assets/img/upload.png"/>
            </div>
          </div>
          <div class="row text-center mt-5 mb-3">
            <div class="col-12">
               <span class="credential">Credential1</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-5"  @click="goToCredential(2)">
      <div class="col-12">
        <div class="card">
          <div class="row text-center mt-5">
            <div class="col-12">
               <img class="credential_img" src="../assets/img/upload.png"/>
            </div>
          </div>
          <div class="row text-center mt-5 mb-3">
            <div class="col-12">
               <span class="credential">Credential2</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container" v-else>
    <div class="row mt-5">
      <div class="col-12 center"><span style="font-weight: bold;">User Uploads</span> </div>
    </div>
    <div class="col-12 mt-3">
      <ul v-for="(img, index) in imageList" :key="index">
        <li><span>{{img.name}},</span><span> Credential{{img.credential_type}},</span><span> {{img.img_real_name}}</span></li>
      </ul>
    </div>
    
    <div class="row">
        <div class="col-12 mt-5 left"><a class="nav-link" href="#"  @click="logout"> back</a></div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "Home",

  data() {
    return {
      success: null,
      error: null,
      imageList: [],
      user: null
    };
  },

  mounted() {
    if (!localStorage.getItem("authToken")) {
      this.$router.push({ name: "Login" });
    }
  },

  created() {
      this.user = JSON.parse(localStorage.getItem("userData"));
      if (this.user.is_admin === 1) {
        this.loadData()
      }
  },

  methods: {
    ...mapActions("auth", ["sendLogoutRequest", "getUserData","getAllImageList"]),

    loadData() {
      this.getAllImageList().then((response) => {
        this.imageList = response.data
      });
    },
    logout() {
      this.sendLogoutRequest();
    },
    goToCredential(type) {
      this.$router.push({
          path: "/credential",
          name: "Credential",
          params: { type: type }
        });
    }
  }
};
</script>

<style>
a {
  color: #007bff ;
}
.credential {
  font-size: 2rem;
  color: #007bff;
  font-weight: 700;
}
.credential_img {
  width: 4rem;
}
.text-center {
  text-align: center;
}
</style>