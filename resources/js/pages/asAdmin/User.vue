<template>
    <section class="body_content">
        <div class="columns is-mobile">

            <!-- user list START -->
            <div class="column">
                <user-list :users="users" 
                    @edit="edit($event)" @del="del($event)" 
                    @getUser="getUser($event)"></user-list>
            </div>
            <!-- user list END -->

            <!-- form START -->
            <div class="column">
                <user-form :roles="roles" @getUser="getUser($event)"
                    :editId="editId" @showBox="showBox($event)"
                    ></user-form>
            </div>
            <!-- form END -->
        </div>
        <!-- modal START -->
        <div class="modal" :class="{'is-active':isModalShow}">
          <div class="modal-background"></div>
          <div class="modal-content">
            <!-- Any other Bulma elements you want -->
            <div class="box">
                <div v-html="res_status"></div>
            </div>
          </div>
          <button class="modal-close is-large" aria-label="close" 
              @click.prevent="isModalShow=''"></button>
        </div>
        <!-- modal END -->
    </section>
</template>
<script>
import UserForm from "./UserForm.vue"
import UserList from "./UserList.vue"

export default{
    name:"AdminUser",
    components:{
        UserForm,
        UserList,
    },
    data(){
        return{
            users:'',
            roles:'',
            editId:'',
            isModalShow:'',
            res_status:'',
        }
    },
    mounted(){
        this.getUser()
    },
    methods:{
        getUser(page){
            this.res_status = ''
            this.editId = 0
            this.isModalShow = ''

            let url =''
            if(page){
                url = page 
                this.$cookies.set('mUser_old_page',url)
            }
            url = this.$cookies.get('mUser_old_page')
            if(!url) url = `/api/admin/getuser`
            axios.get(url)
                .then(res=>{
//                    console.log(res.data)
                    this.users = res.data.user
                    this.roles = res.data.role
                })
        },
        edit(id){
            this.editId = id
        },
        del(id){
            let conf = `Delete user id ${id}?`
            if(id && id != 0 && confirm(conf) == true){
                //alert(`deleted ${id}`)
                let url = `/api/admin/user/${id}`
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        this.isModalShow = 'is-active'

                    })


                setTimeout(()=>{
                    this.hideBox()
                    this.getUser()
                },2500)

            }
            return
        },
        showBox(msg){
            this.res_status = msg
            this.isModalShow = 'is-active'
        },
        hideBox(){
            this.isModalShow = ''
            this.res_status = ''
        },
    },
}
</script>
