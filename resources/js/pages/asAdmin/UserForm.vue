<template>
    <div>
        <form action="">
            <div class="field">
                <div class="control">
                    <label class="label" for="">name</label>
                    <input v-model="uForm.name" class="input" type="text" 
                    ref="name" placeholder="Name...">
                </div>
            </div>

            <div class="field is-pulled-right">
                <div class="control mt-2">
                    <label class="checkbox pb-2" for="">
                        <input v-model="uForm.activeUser" class="is-checkbox" 
                            type="checkbox" name="">

                        <span class="tag is-medium is-warning">
                            active user</span>
                    </label>
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <label class="label" for="">Email</label>
                    <input v-model="uForm.email" class="input" type="email" 
                    placeholder="Email...">
                </div>
            </div>


            <div class="field">
                <div class="control">
                    <label class="label" for="">Password</label>
                    <input v-model="uForm.password" class="input" type="password" 
                    placeholder="~~~~~">
                </div>
            </div>

            <nav class="level">
                <div class="level-item" 
                    v-for="ro in roles">
                    <label class="checkbox" for="">
                        <input v-model="uForm.role" class="is-checkbox is-large" 
                        type="checkbox" :value="ro.id"
                        name="">
                        <span class="ml-2">
                            {{ro.role_name}}
                        </span>
                    </label>
                </div>
            </nav>
            
            <div class="field is-pulled-left">
                <div v-html="res_status"></div>
            </div>
            <div class="field is-pulled-right">
                <div class="buttons">
                    <button class="button is-primary is-rounded is-outlined" 
                        @click.prevent="saveUser(editId)">
                        <font-awesome-icon icon="check"></font-awesome-icon>
                    </button>
                </div>
            </div>

        </form>
    </div>
</template>
<script>
export default{
    name:"UserForm",
    props:["editId","roles"],
    data(){return{
        res_status:'',
        uForm:new Form({
            name:'',
            email:'',
            password:'',
            role:[],
            activeUser:'',
        }),
    }},
    mounted(){

    },
    watch:{
        "editId":function(x){
            this.getEditData(x)
        }
    },
    methods:{
        getEditData(x){
            if(x && x != 0){
                this.$refs.name.focus()
                this.uForm.role = []
                this.uForm.activeUser = false
                let url = `/api/admin/user/${x}`
                axios.get(url)
                    .then(res=>{
                        //console.log(res.data.user)
                        let uData = res.data.user
                        let ro =  uData.role
                        this.uForm.name = uData.name
                        this.uForm.email = uData.email
                        if(uData.email_verified_at != null) this.uForm.activeUser = true
                        ro.map((index)=>{
                            //console.log(`the val is ${index.role_name}`)
                            this.uForm.role.push(index.id)
                        })
                    })
            }
        },
        saveUser(id){
            let url = `/api/admin/user`

            let uData = {
                name:this.uForm.name,
                email:this.uForm.email,
                password:this.uForm.password,
                roles:this.uForm.role,
                activeUser:this.uForm.activeUser
            }
            /*
            // this data cannot work with sync method on update
            let fData = new FormData()
            fData.append('name',this.uForm.name)
            fData.append('email',this.uForm.email)
            fData.append('password',this.uForm.password)
            fData.append('roles',this.uForm.role)

            if(id && id != 0){
                url = `/api/admin/user/${id}`
                fData.append('_method','PUT')
            }

            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium is-danger">
                    ${Object.values(err.response.data.errors)}
                        </span>`
                })
                */

            if(!id || id == 0){
                axios.post(url,uData)
                    .then(res=>{
                        this.res_status = res.data.msg
                        setTimeout(()=>{
                            this.formReset()
                            this.$emit('getUser')
                        },2000)
                    }).
                catch(err=>{
                    this.res_status = `<span class="tag is-medium is-danger">
                    ${Object.values(err.response.data.errors)}
                        </span>`
                })
            }else{
                url = `/api/admin/user/${id}`
                axios.put(url,uData)
                    .then(res=>{
                        this.res_status = res.data.msg
                        setTimeout(()=>{
                            this.formReset()
                            this.$emit('getUser')
                        },2000)
                    }).
                catch(err=>{
                    this.res_status = `<span class="tag is-medium is-danger">
                    ${Object.values(err.response.data.errors)}
                        </span>`
                })
            }
        },
        formReset(){
            this.uForm.reset()
            this.res_status = ''
        },
    },
}
</script>

