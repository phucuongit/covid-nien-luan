<template>
  <div class="login">
    <el-row class="row-bg">
      <el-col :span="12">
        <div class="grid-content">
          <img src="@/assets/images/login-img-left.jpg" alt="not found "> 
        </div>
      </el-col>
      <el-col :span="12">
        <div class="grid-content text-left login-right-wrap">
          <div class="login__right__login">
            <h1>Welcome to my web</h1>
            <div class="state-login" v-if="stateLogin">
              <i class="el-icon-warning"></i>
              Tên đăng nhập hoặc mật khẩu không đúng
            </div>
            
            <div class="login-form">
              <el-form @submit.prevent="onSubmit"
                label-width="120px" label-position="left" class="demo-ruleForm">
                <el-form-item label="Tên đăng nhập">
                  <el-input type="tel" v-model="username"></el-input>
                  <div class="login-error">
                    {{ errors.username }}
                  </div>
                </el-form-item>
                <el-form-item label="Mật khẩu" lable="Quên mật khẩu?">
                  <el-input show-password v-model="password"></el-input>
                  <div class="login-error">
                    {{ errors.password }}
                  </div>
                </el-form-item>
                <el-form-item class="login-button">
                  <el-button type="primary"
                    @click="onSubmit"
                    :loading="loading"
                    :disabled="loading">
                    Đăng nhập
                  </el-button>
                  <el-button @click="resetForm">Đặt lại</el-button>
                </el-form-item>
              </el-form>
            </div>
          </div>
        </div>
        </el-col>
    </el-row>
  </div>
</template>

<script>
import { computed, defineComponent } from "vue"
import { useStore } from "vuex";
import useLogin from "./useLogin.ts"

export default defineComponent({
  setup() {
    const {username, password, errors, resetForm, isLoading, onSubmit } = useLogin();
    const store = useStore();
    const stateLogin = computed(() => store.state.stateLogin)

    const loading = computed (() => {
      if (store.state.stateLogin === true) {
        return false;
      } else {
        return isLoading.value
      }
    })
    
    return {
      username,
      password,
      errors,
      resetForm,
      loading,
      onSubmit,
      stateLogin
    }
  },
})
</script>
