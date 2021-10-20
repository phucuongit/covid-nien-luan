<script lang="ts">
import { defineComponent, onMounted } from "vue"
import useLogin from "./useLogin"
import * as yup from "yup"
import { useForm, useField } from "vee-validate"
import router from "../../router"

export default defineComponent({
  setup() {
    const { login, isLoading } = useLogin()
    const loginSchema = yup.object({
      username: yup.string().required("Tên đăng nhập là bắt buộc"),
      password: yup.string().required("Mật khẩu là bắt buộc")
    })
    const { handleSubmit, errors, resetForm } = useForm({
      validationSchema: loginSchema
    })
    const onSubmit = handleSubmit((values) => {
      if (values) {
        login(values)
      }
    })

    onMounted(async () => {
      if (localStorage.getItem("token")) {
        router.push("/admin")
      }
    })

    const { value: username } = useField("username")
    const { value: password } = useField("password")

    return {
      username,
      password,
      errors,
      resetForm,
      onSubmit,
      isLoading
    }
  }
})
</script>

<template>
  <div class="login">
    <el-row class="row-bg">
      <el-col :xs="24" :sm="24" :md="12">
        <div class="grid-content">
          <img
            src="@/assets/images/login-img-left.jpg"
            alt="not found "
            class="hidden-sm-and-down"
          />
          <img
            src="@/assets/images/login-img-left-mobile.jpg"
            alt="not found "
            class="hidden-md-and-up"
          />
        </div>
      </el-col>
      <el-col :xs="24" :sm="24" :md="12" class="bg-fafafa">
        <div class="grid-content text-left login-right-wrap">
          <div class="login-right-info">
            <h1 class="mb-30">Welcome to my web</h1>
            <div class="login-form">
              <el-form
                @submit="onSubmit"
                label-width="120px"
                label-position="left"
                class="demo-ruleForm"
              >
                <el-form-item label="Tên đăng nhập">
                  <el-input v-model="username" :disabled="isLoading"></el-input>
                  <div class="login-error">
                    {{ errors.username }}
                  </div>
                </el-form-item>
                <el-form-item label="Mật khẩu">
                  <el-input
                    show-password
                    v-model="password"
                    :disabled="isLoading"
                    @keyup.enter="onSubmit"
                  ></el-input>
                  <div class="login-error">
                    {{ errors.password }}
                  </div>
                </el-form-item>
                <el-form-item class="login-button">
                  <el-button
                    type="primary"
                    @click="onSubmit"
                    :loading="isLoading"
                    :disabled="isLoading"
                  >
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
