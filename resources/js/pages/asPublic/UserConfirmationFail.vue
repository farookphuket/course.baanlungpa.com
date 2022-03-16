<template>
    <section class="body_content">
        <div class="container">
            <h1 class="title has-text-centered has-text-danger">
                Sorry! but your link is expired!
            </h1>
            <div class="box mb-4">
                <p>We're so sorry for this but your activation code or link 
                has expired we cannot activate your account please try to 
                register again or you can feel free to visit our website 
                as you wish without having to register or login at all.</p>
            </div>
        </div>
    </section>
</template>

<script>
export default{
    name:"UserConfirmationFail",
    data(){return{
        ckConfirmationCode:'',
    }},
    mounted(){
        this.checkIfUserHasCookie()
    },
    methods:{
        checkIfUserHasCookie(){
            if(this.$cookies.get('user_has_confirmation_code') || 
            this.$cookies.get('user_has_confirmation_code') !== null || 
            this.$cookies.get('token') || 
                this.$cookies.get('token') !== null){
                this.$cookies.remove('token')
                this.$cookies.remove('user_has_confirmation_code')
            }
        },
    },
}
</script>
