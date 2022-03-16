<template>
    <div>
        <!-- modal START -->
        <div class="modal" :class="{'is-active':isModalShow}">
            <div class="modal-background"></div>
            <div class="modal-content">
                <!-- Any other Bulma elements you want -->
                <div class="content box">
                    <div class="mb-4">
                        <p class="title has-text-centered">
                            Please confirm !
                        </p>
                        <p class="subtitle">
                            is this really you?
                        </p>
                        <p>
                            you are about to update or delete your infomation 
                            which need 
                            the confirm from you that you really want to do it.
                        </p>
                    </div>
                    <form action="" class="pb-4">
                        <div class="field">
                            <div class="control">
                                <input v-model="uForm.pass_conf" class="input" 
                                type="password" ref="pass_conf" 
                                @keydown.enter.prevent="checkUser">
                            </div>
                        </div>

                        <div class="mt-4 mb-4">
                            <div class="field is-pulled-left">
                                <div v-html="res_status"></div>
                            </div>

                            <div class="field is-pulled-right">
                                <button class="button is-primary is-rounded 
                                    is-small" 
                                    @click.prevent="checkUser">
                                    <font-awesome-icon icon="check">
                                    </font-awesome-icon>
                                </button>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
            <button class="modal-close is-large" aria-label="close" 
                @click.prevent="isModalShow = ''"></button>
        </div>
        <!-- modal END -->
    </div>
</template>

<script>

export default{
    name:"ConfirmMyPassword",
    data(){return{
        res_status:'',
        isModalShow:'',
        uForm:new Form({
            pass_conf:'',
        }),
    }},
    mounted(){

    },
    methods:{
        checkUser(){
            // checkUser will set the cookies 'user_is_confirmed' to true 
            // if the user enter his correct currently use password
            let url = `/api/member/confirm` 
            let fData = new FormData()
            fData.append("pass_conf",this.uForm.pass_conf)
            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                    let falseStatus = !res.data.user_is_confirmed?true:false
                    if(falseStatus === true){
                        this.$refs.pass_conf.focus()
                    }else{

                        // correct password set 'user_is_confirmed' to true
                        this.$cookies.set('user_is_confirmed',true)

                        setTimeout(()=>{
                            this.isModalShow = ''
                        },2500)
                    }
                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium 
                    has-text-weight-bold has-text-danger">
                        ${Object.values(err.response.data.errors).join()}
                    </span>`
                    setTimeout(()=>{
                        this.res_status = ''
                        this.$refs.pass_conf.focus()
                    },3200)
                })
        },
        showConfirmationBox(){
            this.res_status = ''
            if(!this.$cookies.get('user_is_confirmed') || 
                this.$cookies.get('user_is_confirmed') !== 'true'){
                this.isModalShow = 'is-active'
                setTimeout(()=>{
                    this.$refs.pass_conf.focus()
                },700)
            }else{
                this.isModalShow = ''
            }
        },
    },
}
</script>
