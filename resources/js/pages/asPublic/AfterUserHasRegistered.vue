<template>
    <div>

        



        <!-- dialog box will show after user has registered START -->
        <div class="modal" :class="{'is-active':isModalShow}">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Thank you.</p>
                    <button class="delete" aria-label="close" 
                        @click.prevent="isModalShow = ''"></button>
                </header>
                <section class="modal-card-body">
                    <!-- Content ... -->
                    <div v-if="afterRegis !== false">
                        <h2 class="title has-text-centered">
                            thank you for join us!
                        </h2>

                        <div class="content box">
                            <p class="subtitle">
                                please check your email
                            </p>
                            <p>
                                you have see this message is because you have 
                                registered.
                            </p>
                            <p>
                                to log-in with your account you have to confirm 
                                by click on the confirm link.
                            </p>
                        </div>
                    </div>
                    <div v-else>
                        <div class="box">
                            <p class="title">
                                user not register!
                            </p>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" 
                        @click.prevent="goLogin">yes, I'm in</button>
                    <button class="button" 
                        @click.prevent="isModalShow = ''">Cancel</button>
                </footer>
            </div>
        </div>
        <!-- dialog box will show after user has registered END -->
    </div>
</template>
<script>
export default{
    name:"AfterUserHasRegistered",
    data(){return{
        isModalShow:'',
        afterRegis:false,
        theCode:'',
    }},
    mounted(){

    },
    methods:{
        isUserHasConfirmationCode(){
            if(this.$cookies.get('user_has_confirmation_code') && 
                this.$cookies.get('user_has_confirmation_code') !== null){
                this.afterRegis = true
                this.theCode = this.$cookies.get('user_has_confirmation_code')
            }else{
                this.afterRegis = false
            }
            //console.log(this.$cookies.get('user_has_confirmation_code'))
            this.showThankyouBox()
        },
        goLogin(){
            setTimeout(()=>{
                location.href='/login'
            },700)
        },
        showThankyouBox(){
            if(!this.afterRegis && this.afterRegis !== true){
                this.isModalShow = ''
            }else{
                this.isModalShow = 'is-active'
            }
        },
    },
}
</script>
