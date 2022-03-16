<template>
    <section class="body_content">
        <div class="container">
            <p class="title has-text-centerd">
                {{msg_header}}
            </p>
            <p>{{show_message}} </p>

            <div class="box mt-4 mb-4">
                <form action="">
                    <div class="field">
                        <div class="control">
                            <input v-model="uForm.password" class="input" type="text" 
                            ref="password"
                            placeholder="create new password..."
                            name="">
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <input v-model="uForm.pass_conf" class="input" type="text" 
                            placeholder="your new password again"
                            name="">
                        </div>
                    </div>
                    
                    <div class="field">
                        <div v-html="res_status"></div>
                    </div>

                    <div class="mt-4 mb-4 pb-4">
                        <div class="field is-pulled-right">
                            <button class="button is-primary" 
                                @click.prevent="savePass" 
                                type="submit" :disabled="isDisabled">
                                <font-awesome-icon 
                                    icon="check"></font-awesome-icon>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>
</template>
<script>
export default{
    name:"UserResetPassword",
    data(){return{
        my_time_out:0,
        msg_header:'',
        show_message:'',
        my_command:'',
        res_status:'',
        uForm:new Form({
            password:'',
            pass_conf:'',
        }),
        isDisabled:false,
    }},
    mounted(){
        this.getMyTimeOut()
    },
    methods:{
        getMyTimeOut(){
            if(!window.lsDefault.user_reset_password_token){

                // no token not allow to see this page
                location.href='/login'
              //  console.log(`the key no`)
                
            }

            setTimeout(()=>{
                this.$refs.password.focus()

            },800)
            let url = `/api/my-timeout`
            axios.get(url)
                .then(res=>{
                    this.my_time_out = res.data.time_left

                    if(this.my_time_out <= 0){
                        this.msg_header = "Yo!  time's up"
                        this.show_message = " Sorry! time up"
                        this.isDisabled = true
                        // we can set redirect to forgot password page
                    }else{
                        this.msg_header = `please set your password in 
                            ${this.my_time_out} minute`

                        this.res_status = `<span class="tag is-medium has-text-info 
                        has-text-weight-bold">waiting...</span>`;
                    }

                })

            let cme = 6;
            let checkTime = setInterval(()=>{
                cme--
                this.show_message = ''
                let url = `/api/my-timeout`
                axios.get(url)
                    .then(res=>{
                        this.my_time_out = res.data.time_left
                        if(this.my_time_out <= 0){
                            this.msg_header = "Yo! man tim's up"
                            this.show_message = " Sorry! but your time up! "+cme
                        }else{
                            this.msg_header = `please set your password in ${this.my_time_out} minute`
                            this.show_message = `You have ${this.my_time_out} minute left count ${cme}`
                        }
                        //console.log(`the time call is ${this.my_time_out}`)
                    })
                if(cme < 1 || this.my_time_out < 1){
                    this.show_message += "i finnice "+cme
                    this.isDisabled = true
                    clearInterval(checkTime)
                }
            },45000)
        },
        savePass(){
            if(this.my_time_out != 0){
                let url = `/api/user-has-reset-password`
                let fData = new FormData()
                fData.append('password',this.uForm.password)
                fData.append('pass_conf',this.uForm.pass_conf)
                fData.append('_method','PUT')

                axios.post(url,fData)
                    .then(res=>{
                        this.res_status = res.data.msg
                        setTimeout(()=>{
                            // redirect after success
                            location.href="/login"
                        },3500)
                    })
                    .catch(err=>{
                        this.res_status = `<span class="tag is-medium 
                        has-text-danger has-text-weight-bold">
                            ${Object.values(err.response.data.errors).join()}
                        </span>`
                    })

            }
        },
    },
}
</script>
