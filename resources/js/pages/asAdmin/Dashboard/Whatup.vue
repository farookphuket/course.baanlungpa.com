<template>
    <div>
        <div class="content mb-4 pb-6">
            <div class="field is-pulled-right">
                <button class="button is-primary" 
                    @click="showForm='true'" v-if="showForm == false">
                    <font-awesome-icon icon="plus"></font-awesome-icon>
                </button>
                <button class="button is-danger" 
                        @click="closeRefresh"
                    v-else>
                    <font-awesome-icon icon="times"></font-awesome-icon>
                </button>
            </div>
        </div>


        <div class="mt-6 mb-6 pb-4" 
            v-show="showForm">
            <whatup-form 
                @getWhatup="getWhatup($event)" 
                @closeRefresh="closeRefresh($event)"
                :editId="editId" 
                ></whatup-form>
        </div>
        <div class="columns is-mobile">
            <div class="column">
                <whatup-list :whatup="whatup"
                    @getWhatup="getWhatup($event)" 
                    @edit="edit($event)" 
                    @del="del($event)"
                    ></whatup-list>
            </div><!-- end of div.column show whatup list -->

        </div><!-- end of div.columns -->

        <!-- modal START -->
        <x-box :msg="res_status" ref="xbox"></x-box>
        <!-- modal END -->
    </div>
</template>
<script>
import WhatupForm from './WhatupForm.vue'
import WhatupList from './WhatupList.vue'
import xBox from "../../_include/BoxModal.vue"

export default{
    name:"AdminWhatup",
    components:{
        WhatupForm,
        WhatupList,
        xBox,
    },
    data(){return{
        showForm:false,
        isModalShow:'',
        whatup:'',
        editId:0,
        res_status:'',
    }},
    mounted(){
        this.getWhatup()
    },
    methods:{
        getWhatup(page){
            this.editId = 0
            let url = ''
            if(page){
                url = `${page}&perpage=10`
                this.$cookies.set('wa_old_page',url)
            }
            url = this.$cookies.get('wa_old_page')
            if(!url){
                url = `/api/admin/whatup?perpage=10`
            }
            axios.get(url)
                .then(res=>{
                    this.whatup = res.data.whatup
                    
                })
            
        },
        edit(id){
            this.showForm = true 
            this.editId = id
        },
        del(id){
            if(id && id != 0 && 
                confirm(`delete post id ${id}`) == true){
                let url = `/api/admin/whatup/${id}`
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        //this.isModalShow = 'is-active'
                        this.openBox({msg:this.res_status,timeOut:2500})

                        setTimeout(()=>{
                            this.getWhatup()
                        },3200)
                    })
            }
            return
        },
        openBox({msg,timeOut = 0}){
            this.res_status = msg
            this.$refs.xbox.showBoxModal()
            if(timeOut && timeOut !== 0){
                setTimeout(()=>{
                    this.$refs.xbox.hideBoxModal()
                },timeOut)

            }else{
                //console.log(`the time is ${timeOut}`)
                this.$refs.xbox.hideBoxModal()
            }
        },
        closeRefresh(){
            alert(`the page need to be reload!`)
            setTimeout(()=>{
                location.reload()
            },1500)
        },
    }
}
</script>
