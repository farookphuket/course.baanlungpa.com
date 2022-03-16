<template>
    <div>
        <h2>Logout page</h2>
    </div>
</template>
<script>
export default{
    name:"LogoutPage",
    data(){
        return{
            user:''
        }
    },
    mounted(){
        this.logout()
    },
    methods:{
        logout(){
            let url = `/api/member/logout`
            axios.delete(url)
                .then(res=>{
                    let r_url = res.data.url
                    location.href = r_url
            })
        },
    },
}
</script>
