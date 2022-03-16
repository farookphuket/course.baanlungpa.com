<template>
    <section class="body_content">


        <div class="container">

            <div class="columns">
                <div class="column">
                    <login-agreement></login-agreement>
                </div>
                <div class="column">
                    <form action="">
                        <div class="field">
                            <div class="control">
                                <input v-model="loForm.email" class="input" 
                                type="email" 
                                ref="email"
                                placeholder="E-mail..."
                                >
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <input v-model="loForm.password" class="input" 
                                type="password" 
                                name="" placeholder="~~~~">
                            </div>
                        </div>
                        <!-- show status START -->
                        <div class="field">
                            <div style="padding-left:1px;"  
                                v-html="res_status">
                            </div>
                        </div>
                        <!-- show status START -->

                        <!-- button START -->
                        <div class="mt-4 mb-4 p-2">
                            <div class="field is-pulled-right">

                                <router-link :to="{name:'ForgotPassword'}" 
                                    class="button is-uppercase is-link is-outlined 
                                    is-rounded mr-2 ">
                                    forgot password
                                </router-link>
                                <button class="button is-primary 
                                    is-rounded is-outlined" 
                                    type="submit" 
                                    @click.prevent="login">
                                    <font-awesome-icon icon="fingerprint">
                                    </font-awesome-icon> 
                                    <span class="ml-2">
                                        Login
                                    </span>
                                </button>
                            </div>
                        </div>
                        <!-- button END -->
                    </form>
                </div><!-- end of div.column -->
            </div><!-- end of div.columns -->

        </div><!-- end of div.container -->
    </section>
</template>
<script>
import LoginAgreement from "./Login/LoginAgreement.vue"
export default{
    name:"LoginPage",
    components:{
        LoginAgreement,
    },
    data(){return{
        loForm:{
            email:'',
            password:'',
        },
        res_status:'',
        token:''
    }},
    mounted(){
        setTimeout(()=>{
            this.$refs.email.focus()
        },800)
    },
    methods:{
        login(){
            this.res_status = "submitting..."
            this.$cookies.remove("user_must_verify")
            let r_url = '/user_must_verify'
            let fData = new FormData()
            fData.append("email",this.loForm.email)
            fData.append("password",this.loForm.password)
            let url = `/api/login`
            axios.post(url,fData)
                .then(res=>{
                    let user_must_verify = res.data.user_must_verify 

                    this.res_status = res.data.msg
                    this.token = res.data.access_token
                    this.$cookies.set("token",this.token)
                    if(user_must_verify !== false){
                        this.$cookies.set('user_must_verify',true)
                    }

//                    console.log(res.data)

                    r_url = res.data.url
                    setTimeout(()=>{
                        location.href=r_url
                    },2300)
                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium is-danger">
                        ${Object.values(err.response.data.errors).join()}
                    </span>`
                })
        },
    },
}
</script>
