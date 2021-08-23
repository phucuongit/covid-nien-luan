import * as yup from 'yup';
import { useForm, useField } from 'vee-validate';
import { computed, ref } from 'vue';
import { useStore } from 'vuex';

export default function useLogin () {
    const loginSchema = yup.object ({
      username: yup.string().required("Tên đăng nhập là bắt buộc"),
      password: yup.string().required("Mật khẩu là bắt buộc")
    })
    
    const { handleSubmit, errors, resetForm } = useForm({validationSchema: loginSchema});
    const isLoading = ref (false);
    
    const store = useStore();
    const onSubmit = handleSubmit(values => {
      store.dispatch('login', values)
      isLoading.value = true
    });

    const { value: username } = useField('username');
    const { value: password } = useField('password');
  return {
    username,
    password,
    onSubmit,
    errors,
    resetForm,
    isLoading
  }
};