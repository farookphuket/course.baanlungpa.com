<template>
    <div>
        
            <member-view  
                v-if="userIsMember === true && userIsAdmin === false 
                && userIsLogin === true"></member-view>
            <admin-view v-if="userIsLogin === true && userIsAdmin === true 
                && userIsMember !== true"></admin-view>
            <pub-nav v-if="userIsLogin === false"></pub-nav>
            <public-view v-if="showPubPage === true"></public-view>

        <footer class="footer">
            <div class="container">
                <div class="field">
                    <visitor></visitor>
                </div>
                <div class="field mt-4 pt-4 has-text-centered">
                    <p>
                        CORE V{{core_ver}} (PHP {{php_ver}}) 
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
<script>


import PublicView from './pages/_include/PublicView.vue'
import PubNav from "./pages/_include/PubNav.vue"
import Visitor from "./pages/asPublic/Visitor.vue"
import AdminView from './pages/_include/AdminView.vue'
import MemberView from './pages/_include/MemberView.vue'

export default{
    name:"App",
    components:{
        PubNav,
        PublicView,
        AdminView,
        MemberView,
        Visitor,
    },
    data(){
        return{
            php_ver:'',
            core_ver:'',
            userIsLogin:false,
            userIsAdmin:false,
            userIsMember:false,// will use this for later
            showPubPage:true,
            token:null,
            user_id:false,
            user_must_verify:false,
        }
    },
    mounted(){
        this.dData()
    },
    methods:{
        dData(){
            //console.log(window.lsDefault)
            this.php_ver = window.lsDefault.php_ver
            this.core_ver = window.lsDefault.core_ver
            // get the user passport
            this.checkUserPassport()
            if(!this.$cookies.get("user_must_verify") && 
                this.$cookies.get("user_must_verify") === null){
                this.$cookies.remove("user_must_verify")
//                console.log(`The cookies has remove`) 
            }
        },
        checkUserPassport(){
            this.setDefault()
            let url = `/api/checkuserpassport`
            axios.get(url)
                .then(res=>{
                    
//                    console.log(res.data)
                    let uData = res.data.user
                    if(uData != null){
                        this.showPubPage = false
                        this.userIsLogin = true
                        if(uData.is_admin != 0){
                            this.userIsAdmin = true
                            this.userIsMember = false
                        }else{

                            this.userIsAdmin = false
                            this.userIsMember = true
                        }

                        if(this.$cookies.get("user_must_verify") && 
                            this.$cookies.get("user_must_verify") === true){
                            this.user_must_verify = true
                        }

                    }


                })
        },
        setDefault(){
            this.userIsLogin = false 
            this.showPubPage = true
            this.userIsAdmin = false 
            this.userIsMember = false

        },
    }


}
</script>
