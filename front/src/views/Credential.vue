<template>


  <div class="container">
    <div class="row">
      <div class="col-6"><span style="font-weight: bold;">{{ user.name }}</span> </div>
      <div class="col-6 right"><a class="nav-link" style="padding:0"  href="#"  @click="logout">Logout</a></div>
    </div>
    <div class="row mt-3">
      <div class="col-12 center"><span class="title">Credential {{credentialType}}</span> </div>
    </div>
    <div class="row mt-5">
      <div class="col-12 center"><span style="font-weight: bold;">Preview</span> </div>
      <div class="col-12 center mt-3">
        <div
          class="base-image-input"
          :style="{ 'background-image': `url(${imageData})` }"
          @click="chooseImage"
        >
          <span
            v-if="!imageData"
            class="placeholder"
          >
            Choose an Image
          </span>
          <input
            class="file-input"
            ref="fileInput"
            type="file"
            @input="onSelectFile"
          />
        </div>
      </div>
      <div class="col-12 center mt-3">
        <button type="button" class="btn btn-primary" @click="onUploadImage">Upload File</button>
      </div>
      <div class="col-12 mt-3">
        <ul  v-for="(img, index) in imageList" :key="index" >
          <li><span>{{img.img_real_name}}</span><span class="remove"><a href="#" @click="onDeleteImg(img.id)">remove</a></span></li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-12 mt-5 left"><a class="nav-link" href="#"  @click="$router.go(-1)"> back</a></div>
    </div>
  </div>

</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "Credential",

  data() {
    return {
      success: null,
      error: null,
      credentialType: null,
      previewImage: null,
      imageData: null,
      user: null,
      fileName: null,
      imageList: null
    };
  },

  created() {
    this.user = JSON.parse(localStorage.getItem('userData'));
    if (this.$route.params.type) {
      localStorage.setItem('credentialType', this.$route.params.type)
      this.credentialType = this.$route.params.type;
    } else {
      this.credentialType = localStorage.getItem('credentialType');
    }
    this.loadData();
  },

  methods: {
    ...mapActions("auth", ["uploadIamge","getImageList","deleteImg","sendLogoutRequest"]),
    loadData() {
      this.getImageList({credentialType: this.credentialType, userId: this.user.id}).then((response) => {
        this.imageList = response.data
      });
    },
    logout() {
      this.sendLogoutRequest();
    },
    chooseImage () {
      this.$refs.fileInput.click()
    },
    onSelectFile () {
      const input = this.$refs.fileInput
      const files = input.files
      if (files && files[0]) {
        this.fileName = files[0].name;
        if (files[0].size > 1024 * 1024 * 2) {
           this.$alert('Image too big (> 2MB)');
           return;
        }
        console.log(files[0]);
        if (files[0].type !== 'image/jpeg' && files[0].type !== 'image/png') {
          this.$alert('Invalid Type!');
          return;
        }
        const reader = new FileReader
        reader.onload = e => {
          this.imageData = e.target.result
        }
        reader.readAsDataURL(files[0])
        console.log(this.fileName);
      }
    },
    onUploadImage () {
      if (this.fileName) {
        this.uploadIamge({file: this.imageData, file_real_name: this.fileName, credentialType: this.credentialType, userId: this.user.id}).then(() => {
          this.loadData()
          this.imageData = null
          this.fileName = null
          this.$alert("Uploaded Successfully");
          return;
        });
      } else {
        this.$alert("Please Choose Image");
        return;
      }
    },
    onDeleteImg (id) {
      this.deleteImg({id: id}).then((response) =>{
        if (response.status) {
          this.loadData();
          this.$alert("Deleted Successfully");
        } else {
          this.$alert("Error Occured");
        }
      })
    }
  } 
};
</script>

<style>
a {
  color: #007bff ;
  text-decoration: underline;
}
.credential {
  font-size: 2rem;
  color: #007bff;
  font-weight: 700;
}
.credential_img {
  width: 4rem;
}
.imagePreviewWrapper {
    width: 250px;
    height: 250px;
    display: block;
    cursor: pointer;
    margin: 0 auto 30px;
    background-size: cover;
    background-position: center center;
}
.base-image-input {
  display: block;
  width: 100%;
  height: 250px;
  cursor: pointer;
  background-size: cover;
  background-position: center center;
}
.placeholder {
  background: #F0F0F0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #333;
  font-size: 18px;
  font-family: Helvetica;
}
.placeholder:hover {
  background: #E0E0E0;
}
.file-input {
  display: none;
}
.remove {
  float: right;
}
</style>