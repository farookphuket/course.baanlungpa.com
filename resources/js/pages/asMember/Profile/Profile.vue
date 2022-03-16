<template>
    <section class="body_content">
        <div class="container">
            <div class="columns is-mobile">
                <div class="column">

                    <div class="mb-4">
                        <p class="title">
                            {{user.name}}'s profile
                        </p>
                        <div class="box">
                            <p>this is about {{user.name}}'s info</p>
                        </div>
                    </div>

                    <div class="mt-2">
                        <p class="title has-text-danger">
                            Danger zone
                        </p>
                        <div class="box">
                            <p>
                                if you press delete button the system will delete 
                                every post that you have made and this operation 
                                cannot be undo so please make your backup or lose it 
                                forever.
                            </p>
                        </div>
                        <div class="mt-4 mb-4 pt-4 pb-4">
                            <button class="button is-block is-outlined 
                                is-rounded is-danger is-uppercase" 
                                @click.prevent="deleteMyAccount(user_id)">
                                <font-awesome-icon 
                                    icon="times"></font-awesome-icon>
                                <span class="ml-2">
                                    delete my account
                                </span>
                            </button>
                        </div>
                    </div>

                    
                </div>
                <div class="column">
                    <profile-form :user="user"  
                        @getUser="getUser($event)" 
                        @confMyPass="confMyPass($event)"></profile-form>
                </div>
            </div>
        </div>

        <confirm-my-password ref="confirmBox" 
            @confMyPass="confMyPass($event)"></confirm-my-password>
    </section>
</template>
<script>
import ProfileForm from "./ProfileForm.vue"
import ConfirmMyPassword from "./ConfirmMyPassword.vue" 
export default{
    name:"Profile",
    components:{
        ProfileForm,
        ConfirmMyPassword,
    },
    data(){return{
        user:'',
        user_id:0,
        res_status:'',
        runConfirm:false,
    }},
    mounted(){
        this.getUser()

    },
    methods:{
        getUser(){
            let url = `/api/member/user`
            axios.get(url)
                .then(res=>{
                    //console.log(res.data)
                    this.user = res.data.user
                    this.user_id = this.user.id
                })
        },
        confMyPass(){
            this.$refs.confirmBox.showConfirmationBox()
                
        },
        checkIsUserNeedConfirm(){
            if(!this.$cookies.get('user_is_confirmed') || 
                this.$cookies.get('user_is_confirmed') !== 'true'){
                this.confMyPass()
                return false
            }
            return true
        },
        deleteMyAccount(id){
            if(id && id !== 0 && 
                confirm(`delete your account id ${id}?`) === true && 
            this.checkIsUserNeedConfirm() === true){
                let url = `/api/member/profile/${id}`
            }else{
                return
            }
        },
    },
}
</script>
