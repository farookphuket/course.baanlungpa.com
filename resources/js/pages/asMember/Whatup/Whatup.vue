<template>
    <div class="mt-4 mb-6">

        <div class="container">
            <div class="mb-6 pb-6">
                <p class="title">
                    create new post
                </p>
                <p>click the "plus" button</p>
                <div class="field is-pulled-right">
                    <button class="button is-primary is-outlined" 
                        v-if="showForm == false" 
                        @click.prevent="showForm = true">
                        <font-awesome-icon icon="plus"></font-awesome-icon>
                    </button>
                    <button class="button is-danger is-outlined" 
                            @click.prevent="closeReload"
                        v-else>
                        <font-awesome-icon icon="times"
                            ></font-awesome-icon>
                    </button>
                </div>
            </div>
        </div><!-- end of div.container -->
        
        <!-- show form START -->
        <div class="columns is-mobile">
            <div class="column" v-show="showForm">
                <whatup-form @openBox="openBox($event)"
                    @getWhatup="getWhatup($event)"
                    :editId="editId" 
                    @closeReload="closeReload($event)"></whatup-form>
            </div>
        </div>
        <!-- show form END -->
        <whatup-list :whatup="whatup" 
            @edit="edit($event)" @del="del($event)" 
            @getWhatup="getWhatup($event)"></whatup-list>

        <!-- modal area START -->
        <x-box ref="xbox" 
            :msg="res_status"></x-box>
        <!-- modal area END -->
    </div>
</template>
<script>

import WhatupForm from "./WhatupForm.vue"
import WhatupList from "./WhatupList.vue"

import xBox from "../../_include/BoxModal.vue"

export default{
    name:"Whatup",
    components:{
        WhatupForm,
        WhatupList,
        xBox,
    },
    data(){return{
        showForm:false,
        editId:0,
        whatup:'',
        res_status:'',
        isModalShow:'',
    }},
    mounted(){
        this.getWhatup()
    },
    methods:{
        getWhatup(page){
            let url = ''
            if(page){
                url = page
                this.$cookies.set('mOld_wp_page',url)
            }
            url = this.$cookies.get('mOld_wp_page')
            if(!url) url = `/api/getwhatup`
            axios.get(url)
                .then(res=>{
                    this.whatup = res.data.whatup
                })

        },
        edit(id){
            this.editId = id 
            this.showForm = true
        },
        del(id){
            if(id && id != 0 && 
                confirm(`Delete your post id ${id}`) == true){
                let url = `/api/member/whatup/${id}`
                axios.delete(url)
                    .then(res=>{
                        this.res_status = res.data.msg
                        this.openBox({msg:this.res_status,timeout:2500})

                        setTimeout(()=>{
                            this.getWhatup()
                            this.clearSet()
                        },3200)
                    })
            }
        },
        clearSet(){
            this.res_status = ''
            this.editId = 0
            this.isModalShow = ''
        },
        openBox({msg,timeout}){
            this.res_status = msg
            this.$refs.xbox.showBoxModal()

            if(timeout && timeout !== 0){
                setTimeout(()=>{
                    this.clearSet()
                    this.$refs.xbox.hideBoxModal()
                },timeout)
            }

        },
        closeReload(){
            location.reload()
        },
    },
}
</script>
