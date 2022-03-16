<template>
    <div class="container">
        <form action="" @keydown.enter.prevent="saveUser(owner)">
            <div class="field">
                <div class="control">
                    <input v-model="uForm.name" class="input" type="text" 
                    ref="name">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input v-model="uForm.email" class="input" type="email" 
                    ref="email">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input v-model="uForm.password" class="input" type="password" 
                    placeholder="~~~~">
                </div>
            </div>

            <div class="mt-4 mb-4">
                <div class="field is-pulled-left">
                    <div v-html="res_status"></div>
                </div>
                <div class="field is-pulled-right">
                    <button class="button is-primary is-small is-rounded" 
                        @click.prevent="saveUser(owner)">
                        <font-awesome-icon 
                            icon="check"></font-awesome-icon>
                    </button>
                </div>
            </div>

        </form>

    </div>
</template>
<script>

export default{
    name:"ProfileForm",
    props:["user"],
    data(){return{
        owner:'',
        res_status:'',
        isModalShow:'',
        uForm:new Form({
            name:'',
            email:'',
            password:'',
        }),
    }},
    mounted(){

    },
    watch:{
        "user":function(x){
            this.getUser(x)
        }
    },
    methods:{
        getUser(x){
            //console.log(x.name)
            this.uForm.name = x.name
            this.uForm.email = x.email
            this.owner = x.id

        },
        saveUser(id){
            if(!this.$cookies.get('user_is_confirmed') || 
                this.$cookies.get('user_is_confirmed') === 'false'){
                this.$emit('confMyPass')
                return
            } 
            this.res_status = ''
            let url = `/api/member/profile/${id}`
            let fData = new FormData()
            fData.append("_method","PUT")
            fData.append("name",this.uForm.name)
            fData.append("email",this.uForm.email)
            fData.append("password",this.uForm.password)

            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                    setTimeout(()=>{
                        this.$emit('getUser')
                        this.res_status = ''
                    },2500)
                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium 
                    has-text-danger has-text-weight-bold">
                        ${Object.values(err.response.data.errors).join()}
                    </span>`
                })
        },
    },
}
</script>
