<template>
    <section class="body_content">

        <div class="container">
            <div class="columns is-mobile">
                <div class="column">
                    <p class="title">
                        Dear friends
                    </p>
                    <div class="box">
                        <p>
                            this website has all free content that you can read 
                            as if you want to there is no login or register 
                            required unless you want to write comment or create 
                            your own post to share with another so then you need
                            to register.
                        </p>
                    </div>

                </div>

                <div class="column">

                    <register-form 
                        @isUserHasConfirmationCode="afterRegistered"></register-form>
                </div>

            </div>
        </div>
        <after-user-has-registered 
            ref="runAfterRegister"></after-user-has-registered>
    </section>
</template>
<script>
import AfterUserHasRegistered from './AfterUserHasRegistered.vue'
import RegisterForm from './_RegisterForm.vue'
export default{
    name:"RegisterPage",
    components:{
        RegisterForm,
        AfterUserHasRegistered,
    },
    data(){
        return{
            isModalShow:'',
        } 
    },
    mounted(){

    },
    methods:{
        afterRegistered(){
            this.$refs.runAfterRegister.isUserHasConfirmationCode()
        },
        goLogin(){
            let url = '/login'
            setTimeout(()=>{
                location.href=url
            },3200)
        },
    }
}
</script>
