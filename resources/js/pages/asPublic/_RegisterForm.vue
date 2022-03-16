<template>
    <div>
        <form action="" @keypress.enter.prevent="register">
            <div class="field">
                <div class="control">
                    <input v-model="uForm.name" class="input" type="text" 
                        placeholder="Name..."
                    ref="name">
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <input v-model="uForm.email" class="input" type="email" 
                        placeholder="Email..."
                    ref="email">
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <input v-model="uForm.password" class="input" type="password" 
                        placeholder="~~~~"
                    >
                </div>
            </div>
            <div class="field is-pulled-left pl-2">
                <div v-html="res_status"></div>
            </div>

            <div class="field is-pulled-right pr-2">
                <button type="submit" @click.prevent="register"
                    class="button is-primary is-rounded is-outlined">
                    <font-awesome-icon icon="check"></font-awesome-icon>
                </button>
            </div>
        </form>
    </div>
</template>
<script>
export default{
    name:"RegisterForm",
    data(){return{
        res_status:'',
        hasConfirmationCode:null,
        uForm:new Form({
            email:'',
            name:'',
            password:'',
        }),
    }},
    mounted(){
        this.isUserHasConfirmationCode()
        setTimeout(()=>{
            this.$refs.name.focus()
        },1500)
    },
    methods:{
        register(){
            this.hasConfirmationCode = null
            let url = `/api/register`
            let uData = new FormData()
            uData.append('name',this.uForm.name)
            uData.append('email',this.uForm.email)
            uData.append('password',this.uForm.password)
            axios.post(url,uData)
                .then(res=>{
                    this.res_status = res.data.msg
                    this.hasConfirmationCode = res.data.confirmation_code
                    console.log(`the code is ${this.hasConfirmationCode}`)
                    this.$cookies.set("user_has_confirmation_code",
                        this.hasConfirmationCode)
                    setTimeout(()=>{
                        this.res_status = ''
                    },3200)
                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium is-danger">
                        ${Object.values(err.response.data.errors).join()}
                        </span>`
                })


        },
        isUserHasConfirmationCode(){
            this.$emit('isUserHasConfirmationCode')
        },

    },
}
</script>
