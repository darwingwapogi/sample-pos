<template>
    <div id="login-body">
        <div class="wrapper">
            <h1>Login</h1>
            <div v-if="errorMessage" class="error-msg">Please enter a correct username and password!</div>
            <div class="input-box">
                <input 
                    type="text"
                    placeholder="Username"
                    v-model="state.username"
                    :class="{'is-invalid': v$.username.$error}"
                    @keydown="errorMessage = null"
                    @keydown.enter="login"
                >
                <i class="bx bxs-user"></i>
                <div v-if="v$.username.$error">
                    {{ v$.username.$errors[0].$message }}
                </div>
            </div>
            <div class="input-box">
                <input 
                    type="password"
                    placeholder="Password"
                    v-model="state.password"
                    :class="{'is-invalid': v$.password.$error}"
                    @keydown="errorMessage = null"
                    @keydown.enter="login"
                >
                <i class="bx bxs-lock-alt"></i>
                <div v-if="v$.password.$error">
                    {{ v$.password.$errors[0].$message }}
                </div>
            </div>
            <button class="btn" :disabled="loginClicked" @click="login">
                <i v-if="loginClicked" class='bx bx-loader bx-spin bx-xs'></i>
                {{loginClicked ? "Please wait..." : "Login"}}
            </button>
        </div>
    </div>
</template>
<script setup>
    import { useVuelidate } from '@vuelidate/core';
    import { required, email } from '@vuelidate/validators';
    import { ref, reactive, defineComponent } from 'vue';
    import authService from '@/services/auth.service';
    import _ from 'underscore';

    const state = reactive({});
    let errorMessage = ref(false);
    let loginClicked = ref(false);
    let loginSuccess = ref(false);

    const rules = {
      username: { required },
      password: { required }
    }

    const v$ = useVuelidate(rules, state)


    const login = async() => {
        
        v$.value.$validate();

        if (v$.value.$invalid) {
            return;
        }

        loginClicked.value = true;

        const params = {
            username: state.username,
            password: state.password,
        }

        try {
            const response = await authService.postLogin(params);

            const { token } = response.data;
            loginClicked.value = false;
            loginSuccess.value = true;
            window.localStorage.setItem('jwt-token', token);
            window.location.reload();
            window.location.href = "/dashboard";
        } catch (error) {
            loginClicked.value = false;
            errorMessage.value = true;
        }

    }
</script>
<style scoped>
button.btn:disabled {
    opacity: 100%;
}
</style>