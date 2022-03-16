<template>
    <div class="content">
        <form action="">
            <div class="mb-4 pb-4">
                <div class="field">
                    <div class="control has-icons-right">
                        <input v-model="wForm.wp_title" class="input" type="text" 
                        ref="title" placeholder="title...">
                        <span class="icon is-small is-right has-text-danger 
                        has-text-weight-bold" 
                           v-if="wForm.wp_title.length >= 81">
                                {{wForm.wp_title.length}}
                        </span>
                    <span class="icon is-small is-right" 
                          v-else>
                          {{wForm.wp_title.length}}</span>

                    </div>
                </div>
            </div>


            <div class="mt-4  pt-4">
                <div class="field">
                    <div class="control">
                        <jodit-editor 
                            height=450 
                            v-model="wForm.wp_body" 
                            :editorOptions="options"
                            ></jodit-editor>
                        
                    </div>
                </div>
                <div class="box mt-6 mb-6">
                    <span class="is-pulled-right" v-if="wForm.wp_body.length >= 20">
                            <button class="button is-rounded is-small 
                                is-outlined is-primary" 
                                @click.prevent="willCopy">
                                <font-awesome-icon icon="copy"></font-awesome-icon>
                            </button>
                     </span>
                    <div class="field ">

                        <div class="control ">
                            <textarea v-model="wForm.wp_body" class="textarea" 
                                ref="body"></textarea>

                            
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="mt-4 mb-4 pb-4">
                <div class="columns is-mobile">
                    <div class="column">
                        <div class="field">
                            <div v-html="res_status"></div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field is-pulled-right">
                            <button class="button is-primary is-rounded 
                                is-outlined" @click.prevent="saveWp(editId)">
                                <font-awesome-icon 
                                    icon="check"></font-awesome-icon>
                            </button>
                            <button class="button is-danger is-rounded 
                                is-outlined" 
                                @click.prevent="$emit('closeRefresh')">
                                <font-awesome-icon 
                                    icon="times"></font-awesome-icon>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>
</template>
<script>

import JoditEditor from "jodit-vue3"

export default{
    name:"WhatupForm",
    props:["editId"],
    data(){return{
        res_status:'',
        btnSelect:false,
        wForm:new Form({
            wp_title:'',
            wp_body:'', 
            is_public:'',
        }),
        options:{
            askBeforePasteHTML:false,
            disablePlugins:"powered-by-jodit"
        },
    }},
    mounted(){

    },
    watch:{
        "editId":function(x){
            this.getEditData(x)
        }
    },
    methods:{
        getEditData(id){
            if(id && id != 0){
                setTimeout(()=>{
                    this.$refs.title.focus()
                    this.btnSelect = true
                },700)
                this.wForm.is_public = false
                let url = `/api/admin/whatup/${id}`
                axios.get(url)
                    .then(res=>{
                        console.log(res.data)
                        let rData = res.data.whatup
                        this.wForm.wp_title = rData.wp_title
                        this.wForm.wp_body = rData.wp_body
                        if(rData.is_public !== 0){
                            this.wForm.is_public = true
                        }
                    })
            }
            return
        },
        saveWp(id){
            let url = `/api/admin/whatup`
            let fData = new FormData()
            fData.append("wp_title",this.wForm.wp_title)
            fData.append("wp_body",this.wForm.wp_body)
            if(!this.wForm.is_public){
                this.wForm.is_public = 0
            }
            fData.append("is_public",this.wForm.is_public)

            if(id){
                fData.append("_method","PUT")
                url = `/api/admin/whatup/${id}`

            }
            axios.post(url,fData)
                .then(res=>{
                    this.res_status = res.data.msg
                    setTimeout(()=>{
                        this.formReset()
                        this.$emit('getWhatup')
                    },2500)
                })
                .catch(err=>{
                    this.res_status = `<span class="tag is-medium is-danger">
                        ${Object.values(err.response.data.errors).join()}
                    </span>`
                })
        },
        formReset(){
            this.res_status = ''
            this.wForm.reset()
            this.wForm.wp_body = ''
        },
        willCopy(){
            this.$refs.body.select()
            document.execCommand('copy')
        },
    },
}
</script>
