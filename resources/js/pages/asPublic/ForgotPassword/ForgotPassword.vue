<template>
    <section class="body_content">
        <div class="container">

            <div class="columns is-mobile">
                <!-- div.column show message START -->
                <div class="column">
                    <div class="box content">
                        <p class="title">for password</p>
                        <p>
                            this will sent the reset link to your email.
                        </p>
                        <p>
                            please enter your email then hit Enter and go check 
                            you email.
                        </p>
                    </div>
                </div>
                <!-- div.column show message END -->
                <div class="column">
                    <div class="content mt-4 mb-4">
                        <form class="mb-4" action="" 
                            >
                            <div class="field">
                                <div class="control">
                                    <input v-model="uForm.email" class="input" type="email" 
                                    ref="email" placeholder="Email..."
                                    @keydown.enter.prevent="sentResetPassword"
                                    name="">
                                </div>
                            </div>

                            <!-- show status message START -->
                            <div class="field">
                                <div v-html="res_status">
                                </div>
                            </div>
                            <!-- show status message END -->
                            <div class="mb-4 mt-4">
                                <div class="field is-pulled-right">
                                    <button class="button is-primary 
                                        is-rounded is-outlined" 
                                        @click.prevent="sentResetPassword">
                                        <font-awesome-icon 
                                            icon="check"></font-awesome-icon>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- end of div.column form -->
            </div><!-- end of div.columns -->

        </div>
    </section>
</template>
<script>

export default{
    name:"ForgotPassword",
    data(){return{
        res_status:'',
        uForm:new Form({
            email:'',
        }),
    }},
    mounted(){
        setTimeout(()=>{
            this.$refs.email.focus()
        },800)
    },
    methods:{
        sentResetPassword(){
            let url = `/api/forgotpassword`
            let fData = new FormData()
            fData.append('email',this.uForm.email)
            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium 
                    has-text-weight-bold has-text-danger">
                        ${Object.values(err.response.data.errors).join()}
                    </span>`
                })
        },
    },
}
</script>
